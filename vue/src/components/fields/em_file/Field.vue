<template>
	<div class="em-file-item-col" @click="openPopup()">
		<div
			class="em-file-item-wrapper"
			v-for="item in localValue"
			v-if="!item.noShow"
		>
			<img v-if="item.type == 'image'" :src="item.sizes.small.path" alt=""/>
			<svg v-else>
				<use xlink:href="#attachment"></use>
			</svg>
		</div>
		<template v-if="countFiels == 0">
			<span class="el-empty">{{$t('empty')}}</span>
		</template>
		<div class="em-file__edit" v-if="showPopup" v-click-outside="closePopup">
			<div
				class="em-file__edit-item"
				v-for="(item, index) in localValue"
				v-if="!item.noShow"
			>
				<img v-if="item.type == 'image'" class="em-file__edit-attach" :src="item.sizes.small.path" alt=""/>
				<svg v-else class="em-file__edit-attach">
					<use xlink:href="#attachment"></use>
				</svg>
				<a :href="item.path" target="_blank">Download</a>
				<a href="javascript:void(0);" @click="removeFile(index)">{{$t('remove')}}</a>
			</div>
			<template v-if="countFiels == 0">
				<div class="em-file__empty-pop">
					<span class="el-empty">{{$t('fieldEmFile.no_files')}}</span>
				</div>
			</template>
			<button class="el-gbtn" @click="openSubPopup()">{{$t('fieldEmFile.add_file')}}</button>
			<div class="em-file__upload-popup"
				v-if="showSubPopup"
				v-click-outside="closeSubPopup"
			>
				<div class="em-file__upload-tab-wrapper">
					<div class="em-file__upload-tabs-head">
						<div
							class="em-file__upload-tab-item"
							@click="setActiveTab(item)"
							v-for="item in tabs"
							:class="{active: item.active}"
						>{{item.name}}</div>
					</div>
					<div class="em-file__upload-tabs-content-wrapper">
						<div class="em-file__progress-tab" v-if="isUploading">
							<div class="em-file__progressbar">
								<div class="em-file__progressbar-text">{{ percentUploadOfProgress }}%</div>
								<div
									class="em-file__progressbar-done"
									:style="{ width: percentUploadOfProgress+'%' }"
								></div>
							</div>
							<button @click=cancelUpload class="el-gbtn">{{ $t('cancel') }}</button>
						</div>
						<template v-else>
							<div class="em-file__file-tab" v-if="activeTab == $t('upload')">
								<input type="file" multiple="true" name="file" ref="emFile" @change="uploadFile('file')" id="file" class="em-file" />
								<label class="el-btn" for="file">{{$t('fieldEmFile.choose_file')}}</label>
							</div>
							<div
								class="em-file__link-tab"
								v-if="activeTab == $t('upload_by_link')"
							>
								<input
									class="el-inp em-file__embed-input"
									type="text"
									:placeholder="$t('paste_link')"
									v-model="link"
									@change="uploadFile('link')"
								/>
								<button @click="uploadFile('link')" class="el-btn">{{$t('fieldEmFile.embed_link')}}</button>
							</div>
						</template>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import axios from 'axios';

	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				localValue   : false,
				showPopup    : false,
				showSubPopup : false,
				tabs:
				[
					{ name: this.$t('upload'), active: true },
					{ name: this.$t('upload_by_link'), active: false },
				],
				activeTab: this.$t('upload'),
				link: '',
				percentUploadOfProgress : 0,
				isUploading             : false,
				cancelToken             : null,
			};
		},
		computed:
		{
			countFiels()
			{
				var count = 0;

				if(!this.localValue)
					return count;

				for(var index in this.localValue)
				{
					if(!this.localValue[index].noShow)
						count++;
				}

				return count;
			},
			fieldCode()
			{
				if(typeof this.fieldSettings.fieldCode !== 'undefined')
					return this.fieldSettings.fieldCode;

				return false;
			},
			tableCode()
			{
				if(typeof this.fieldSettings.tableCode !== 'undefined')
					return this.fieldSettings.tableCode;

				return false;
			},
		},
		methods:
		{
			/**
			 * Загрузить изображение
			 */
			async uploadFile(type)
			{
				if(!this.localValue)
					this.$set(this, 'localValue', []);

				let formData   = new FormData();

				formData.append('tableCode', this.tableCode);
				formData.append('fieldCode', this.fieldCode);

				if(type == 'link')
				{
					formData.append('typeUpload', 'link');
					formData.append('link', this.link);
				}
				else if(type == 'file')
				{
					if (typeof this.$refs.emFile == 'undefined' || this.$refs.emFile.length == 0)
						return;

					for(var file of this.$refs.emFile.files)
						formData.append(`${this.fieldCode}[]`, file);

					formData.append('typeUpload', 'file');
				}
				else
					return;

				if(this.view == 'table')
					formData.append('prepareForSave', true);

				this.cancelToken = axios.CancelToken.source();

				this.setUploadProgressStatus(true);
				let result = await axios({
					method           : 'POST',
					data             : formData,
					headers          : { 'Content-Type': 'multipart/form-data' },
					url              : '/field/em_file/index/upload/',
					cancelToken      : this.cancelToken.token,
					onUploadProgress : (e) =>
					{
						this.updateUploadProgress({ loaded: e.loaded, total: e.total });
					},
				}).catch(err =>
				{
					if (err.__proto__.__CANCEL__)
						return { data: {
							success: false,
							message: err.message,
						}};
				});
				this.setUploadProgressStatus(false);

				if(!result.data.success)
				{
					this.ElMessage.error(result.data.message);
					return false;
				}

				for(var newFile of result.data.value)
					this.localValue.push(newFile);

				this.sendValue();
				this.closeSubPopup();
			},

			/**
			 * (де)активирует отображение процесса загрузки
			 */
			setUploadProgressStatus(active)
			{
				this.clearUploadProgress();
				this.$set(this, 'isUploading', !!active);
			},
			/**
			 * обновляет отображение загрузки
			 */
			updateUploadProgress({ percent = 0, loaded = 0, total = 0 })
			{
				if (!percent && total)
					percent = Math.floor((loaded * 100) / total);

				if (percent < 0)
					this.$set(this, 'percentUploadOfProgress', 0);
				else if (percent > 100)
					this.$set(this, 'percentUploadOfProgress', 100);
				else
					this.$set(this, 'percentUploadOfProgress', percent);
			},
			/**
			 * очищает прогресс загрузки
			 */
			clearUploadProgress()
			{
				this.$set(this, 'percentUploadOfProgress', 0);
			},
			/**
			 * отмена загрузки
			 */
			async cancelUpload()
			{
				if (this.cancelToken)
				{
					const result = await this.cancelToken.cancel(this.$t('fieldEmFile.uploading_canceled_by_the_user'));
					this.cancelToken = null;
				}
				this.clearUploadProgress();
			},
			/**
			 * Удалить файл
			 */
			async removeFile(index)
			{
				if(typeof this.localValue[index].new != 'undefined')
				{
					this.$delete(this.localValue, index);
					this.sendValue();
					return;
				}

				this.$set(this.localValue[index], 'delete', true);
				this.$set(this.localValue[index], 'noShow', true);

				this.sendValue();
			},

			/**
			 * Отправить измененное значение родителю
			 */
			sendValue(newValue)
			{
				this.$emit('onChange', {
					value     : this.localValue,
					settings  : this.fieldSettings,
					tableCode : this.tableCode,
					fieldCode : this.fieldCode
				});
			},

			/**
			 * Установить превью для url
			 */
			setPreviewForImage(url, index)
			{
				if(url.match(/\.(jpeg|jpg|gif|png)$/) == null)
					url = false;

				this.setPreview(url, index);
			},

			/**
			 * Установать превью
			 */
			setPreview(url, index)
			{
				var item = this.localValue[index];

				item.type  = (url == false) ? 'no-image' : 'image';
				item.sizes = {
					small: {
						path: url
					}
				};

				this.$set(this.localValue, index, item);
			},

			/**
			 * Задать активность табу
			 */
			setActiveTab(tab)
			{
				if (this.isUploading) return false;
				for (var item of this.tabs)
					item.active = false;

				tab.active = true;
				this.activeTab = tab.name;
			},

			/**
			 * Закрыть/Открыть попап
			 */
			openPopup()
			{
				this.showPopup = true;
			},

			/**
			 * Закрыть/Открыть попап
			 */
			openSubPopup()
			{
				this.showSubPopup = true;
			},

			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
				this.closeSubPopup();
			},

			/**
			 * Закрыть попап
			 */
			closeSubPopup()
			{
				this.showSubPopup = false;
			},
		},
		watch:
		{
			/**
			 * Отслеживать изменение значения у родителя
			 */
			'fieldValue'(newValue)
			{
				this.localValue = newValue;
			},
		},
		/**
		 * Функция создания компонента
		 */
		created()
		{
			this.localValue = this.fieldValue;
		},
	};
</script>
<style lang="scss">
	.em-file-item-col
	{
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		position: absolute;
		left:0px;
		top:0px;
		height: 100%;
		width:100%;
		padding-left:10px;
		cursor: pointer;
	}
	.detail-field-box .em-file-item-col{padding-left:0px;}
	.em-file-item-wrapper
	{
		width: 14px;
		height: 14px;
		margin-right: 3px;
		cursor: pointer;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
		svg
		{
			width:100%;
			height:100%;
			fill: #677387;
		}
	}
	.em-file__upload-popup
	{
		background: #FFFFFF;
		border: 1px solid rgba(103, 115, 135, 0.1);
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border-radius: 2px;
		width: 326px;
		position: absolute;
		padding-top:5px;
		z-index: 5;
	    top:calc(100% + 5px);
	    left: 50%;
	    margin-left:-163px;
	}
	.em-file__upload-tab-item
	{
		font-size: 10px;
		color: rgba(25, 28, 33, 0.7);;
		padding: 0 6px;
		height: 25px;
		line-height: 25px;
		margin-right: 8px;
		border-bottom: 2px solid transparent;
		cursor: pointer;
		&:last-child {margin-right: unset; }
		&.active {border-bottom: 2px solid #191C21; color: #191C21; }
	}
	.em-file__upload-tabs-head
	{
		display: flex;
		height: 25px;
		align-items: center;
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
	}
	.em-file__upload-tab-wrapper {padding: 0 9px; }
	.em-file__upload-tabs-content-wrapper
	{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.em-file__link-tab{width:255px;margin:0 auto; text-align: right; padding:22px 0;}
	.em-file__embed-input {width:255px; margin-bottom: 10px;}
	.em-file
	{
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
	}
	.em-file__file-tab{padding:22px 0; }

	.em-file__edit
	{
		position: absolute;
		top:-5px;
		left:-5px;
		width:calc(100% + 10px);
		min-width: 169px;
		min-height: 60px;
		background: #fff;
		padding-left:10px;
		z-index: 1;
		border-radius: 2px;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		border: 1px solid rgba(103, 115, 135, 0.1);
		padding-bottom:10px;
	}
	.em-file__empty-pop{height: 50px;line-height: 50px;}
	.em-file__edit-item
	{
		padding:7px 0;
		display: flex;
		word-break: break-word;
		align-items: center;
		a{
		    color: rgba(25, 28, 33, 0.4);
		    text-decoration: none;
		    font-size: 12px;
		    margin-right:5px;
		    &:hover{text-decoration: underline;}
		}
	}
	.em-file__edit-attach
	{
		width:25px;
		height: 25px;
		margin-right: 10px;
		border:1px solid rgba(103, 115, 135, 0.3);
		border-radius: 2px;
		fill:rgba(103, 115, 135, 0.3);
	}
	.em-file__progress-tab
	{
		width: 100%;
		padding: 25px 12px;
		display: flex;
		flex-direction: column;
		button { margin-left: auto; }
	}
	.em-file__progressbar
	{
		position: relative;
		width: 100%;
		background-color: rgba(103, 115, 135, 0.1);
		height: 20px;
		margin-bottom: 18px;
		overflow: hidden;
	}
	.em-file__progressbar-text
	{
		font-size: 8px;
		line-height: 11px;
		position: absolute;
		left: 7px;
		top: 5px;
	}
	.em-file__progressbar-done
	{
		height: 100%;
		background-color: rgba(103, 115, 135, 0.1);
		transition: width 0.5s ease-out;
	}
</style>
