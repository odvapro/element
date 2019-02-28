<template>
	<div class="em-file-item-col">
		<div class="em-file-item-wrapper" v-for="item in dataField" v-if="dataField">
			<img :src="item.type == 'image' ? item.sizes.small : '/images/fileicon.png'" alt=""/>
		</div>
		<div class="em-file__add-button" @click.stop="togglePopup()">
			<svg width="17" height="17">
				<use xlink:href="#add-button"></use>
			</svg>
		</div>
		<div class="em-file__upload-popup" @click.stop v-if="showPopup" v-click-outside="closePopup">
			<div class="em-file__upload-popup-head">
				Add file
			</div>
			<form id="ww">
			<div class="em-file__upload-tab-wrapper">
				<div class="em-file__upload-tabs-head">
					<div class="em-file__upload-tab-item" @click="setActiveTab(item)" v-for="item in tabs" :class="{active: item.active}">{{item.name}}</div>
				</div>
				<div class="em-file__upload-tabs-content-wrapper">
					<div class="ulpoad-tab-content" v-if="activeTab == 'Upload'">
						<div class="upload-em-file-wrapper">
							<div class="em-file-wrapper">
								<input type="file" multiple="true" name="file" ref="emFile" @change="uploadFile('file')" id="file" class="em-file" />
								<label for="file">Choose File</label>
							</div>
						</div>
					</div>
					<div class="em-file__upload-tab-content" v-if="activeTab == 'Upload by link'">
						<div class="em-file__upload-tab-input-wrapper">
							<input type="text" placeholder="Paste link" v-model="link" @change="uploadFile('link')">
						</div>
					</div>
				</div>
			</div>
			</form>
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
				showPopup: false,
				dataField: false,
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
					url    : '/api/field/em_file/index/upload/'
				});

				if (!result.data.success)
					return false;

				this.dataField = result.data.value;
				this.closePopup();
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
			togglePopup()
			{
				this.showPopup = !this.showPopup;
			},
			/**
			 * Закрыть попап
			 */
			closePopup()
			{
				this.showPopup = false;
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
	.em-file__add-button
	{
		width: 17px;
		height: 17px;
		cursor: pointer;
	}
	.em-file-item-col
	{
		display: flex;
		flex-wrap: wrap;
		align-items: center;
	}
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
		border-radius: 2px;
		width: 326px;
		position: absolute;
		z-index: 2;
		top: -1px;
		left: -1px;
	}
	.em-file__upload-popup-head
	{
		height: 49px;
		background: rgba(103, 115, 135, 0.1);
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		padding: 0 9px;
		color: rgba(25, 28, 33, 0.4);
		font-size: 10px;
		display: flex;
		align-items: center;
		margin-bottom: 5px;
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
		&:last-child
		{
			margin-right: unset;
		}
		&.active
		{
			border-bottom: 2px solid #191C21;
			color: #191C21;
		}
	}
	.em-file__upload-tabs-head
	{
		display: flex;
		height: 25px;
		align-items: center;
		border-bottom: 2px solid rgba(103, 115, 135, 0.1);
	}
	.em-file__upload-tab-wrapper
	{
		padding: 0 9px;
	}
	.em-file__upload-tabs-content-wrapper
	{
		height: 68px;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.em-file__upload-tab-input-wrapper
	{
		background: #FFFFFF;
		border-radius: 2px;
		height: 30px;
		min-width: 255px;
		input
		{
			border: 1px solid rgba(103, 115, 135, 0.1);
			font-size: 10px;
			padding: 0 11px;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
		}
	}
	.em-file
	{
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
	}
	.em-file + label
	{
		font-size: 12px;
		padding: 7px 11px;
		line-height: 14px;
		color: white;
		background: rgba(25, 28, 33, 0.7);
		border-radius: 2px;
		display: inline-block;
		cursor: pointer;
	}

	.em-file:focus + label,
	.em-file + label:hover
	{
		background: rgba(25, 28, 33, 0.5);
	}
</style>