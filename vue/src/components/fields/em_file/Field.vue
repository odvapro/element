<template>
	<div class="em-file-item-col" @click="openPopup()">
		<div class="em-file-item-wrapper" v-for="item in dataField">
			<img :src="item.type == 'image' ? item.sizes.small : '/images/fileicon.png'" alt=""/>
		</div>
		<template v-if="!dataField">
			<span class="el-empty">Empty</span>
		</template>
		<div class="em-file__edit" v-if="showPopup" v-click-outside="closePopup">
			<div class="em-file__edit-item" v-for="(item, index) in dataField">
				<img class="em-file__edit-attach" :src="item.type == 'image' ? item.sizes.small : '/images/fileicon.png'" alt=""/>
				<a href="javascript:void(0);" @click="removeFile(`${item.upName}`)">remove</a>
			</div>
			<template v-if="!dataField">
				<div class="em-file__empty-pop">
					<span class="el-empty">No files</span>
				</div>
			</template>
			<button class="el-gbtn" @click="openSubPopup()">Add file</button>
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
						<div class="em-file__file-tab" v-if="activeTab == 'Upload'">
							<input type="file" multiple="true" name="file" ref="emFile" @change="uploadFile('file')" id="file" class="em-file" />
							<label class="el-btn" for="file">Choose File</label>
						</div>
						<div
							class="em-file__link-tab"
							v-if="activeTab == 'Upload by link'"
						>
							<input
								class="el-inp em-file__embed-input"
								type="text"
								placeholder="Paste link"
								v-model="link"
								@change="uploadFile('link')"
							/>
							<button class="el-btn">Embed Link</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue', 'fieldSettings'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				showPopup    : false,
				showSubPopup : false,
				dataField    : false,
				tabs:
				[
					{ name: 'Upload', active: true },
					{ name: 'Upload by link', active: false },
				],
				activeTab: 'Upload',
				link: ''
			}
		},
		methods:
		{
			/**
			 * Загрузить изображение
			 */
			async uploadFile(type)
			{
				let formData = new FormData();

				formData.append('tableCode', this.fieldSettings.tableCode);
				formData.append('fieldCode', this.fieldSettings.fieldCode);
				formData.append('primaryKey', this.fieldSettings.primaryKey.fieldCode);
				formData.append('primaryKeyValue', this.fieldSettings.primaryKey.value);
				formData.append('typeUpload', type);
				formData.append('link', this.link);

				if (typeof this.$refs.emFile != 'undefined')
					for (var file of this.$refs.emFile.files)
						formData.append('files' + file.name, file);

				let result = await this.$axios({
					method : 'POST',
					data   : formData,
					headers: { 'Content-Type': 'multipart/form-data' },
					url    : '/field/em_file/index/upload/'
				});

				if (!result.data.success)
					return false;

				/*this.$emit('onChange', {
					value    : result.data.value,
					settings : this.fieldSettings
				});*/

				this.closePopup();
			},

			/**
			 * Удалить файл
			 */
			async removeFile(fileName)
			{
				let formData = new FormData();
				formData.append('tableCode', this.fieldSettings.tableCode);
				formData.append('fieldCode', this.fieldSettings.fieldCode);
				formData.append('primaryKey', this.fieldSettings.primaryKey.fieldCode);
				formData.append('primaryKeyValue', this.fieldSettings.primaryKey.value);
				formData.append('fileName', fileName);

				let result = await this.$axios({
					method : 'POST',
					data   : formData,
					headers: { 'Content-Type': 'multipart/form-data' },
					url    : '/field/em_file/index/delete/'
				});

				if (!result.data.success)
					return false;

				this.$emit('onChange', {
					value    : result.data.value,
					settings : this.fieldSettings
				});
			},

			/**
			 * Задать активность табу
			 */
			setActiveTab(tab)
			{
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
			},

			/**
			 * Закрыть попап
			 */
			closeSubPopup()
			{
				this.showSubPopup = false;
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.dataField = this.fieldValue;
		}
	}
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
		    &:hover{text-decoration: underline;}
		}
	}
	.em-file__edit-attach
	{
		width:25px;
		margin-right: 10px;
		border:1px solid rgba(103, 115, 135, 0.3);
		border-radius: 2px;
	}
</style>