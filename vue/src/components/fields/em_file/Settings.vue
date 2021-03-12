<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmFile.settings.folder_for_save')}}
				<small v-if="errors.savePath" class="popup__field-error">{{ errors.savePath.message }}</small>
			</div>
			<div class="popup__field-input em-file__settings-path">
				<span>{{ settings.rootPath }}</span>
				<input
					type="text"
					@change="checkPath"
					v-model="localSettings.savePath"
					:placeholder="$t('fieldEmFile.settings.folder_for_save')"
				/>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmFile.settings.resolutions')}}
				<small v-if="errors.nodeFieldCode" class="popup__field-error">{{ errors.nodeFieldCode.message }}</small>
			</div>
		</div>
		<div class="em-file__s-resolutions">
			<div class="em-file__s-resolution">
				<div class="em-file__s-code">{{$t('code')}}</div>
				<div class="em-file__s-width">{{$t('fieldEmFile.settings.width')}}</div>
				<div class="em-file__s-height">{{$t('fieldEmFile.settings.height')}}</div>
				<div class="em-file__s-remove"></div>
			</div>
			<div class="em-file__s-resolution" v-for="listItem, index in localSettings.resolutions" :key="index">
				<div class="em-file__s-code">
					<input type="text" class="el-inp-noborder" v-model="listItem.code" :placeholder="$t('code')" :disabled="listItem.required">
				</div>
				<div class="em-file__s-width">
					<input type="number" class="el-inp-noborder" v-model.number="listItem.width" :placeholder="$t('fieldEmFile.settings.width')">
				</div>
				<div class="em-file__s-height">
					<input type="number" class="el-inp-noborder" v-model.number="listItem.height" :placeholder="$t('fieldEmFile.settings.height')">
				</div>
				<div class="em-file__s-remove" v-if="!listItem.required">
					<div class="em-file__s-remove-button" @click.stop="removeResolution(index)">
						<svg width="12" height="12">
							<use xlink:href="#plus-white"></use>
						</svg>
					</div>
				</div>
				<small
					v-if="errors.resolutions && errors.resolutions[index]"
					class="em-file__s-error"
				>{{ errors.resolutions[index].message }}</small>
			</div>
		</div>
		<button class="el-gbtn" @click="addResolution()">{{$t('fieldEmFile.settings.add_resolution')}}</button>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">{{$t('cancel')}}</button>
			<button @click="save()" class="el-btn">{{$t('save_settings')}}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				localSettings :
				{
					savePath    : 'element/public/upload/',
					resolutions : [
						{code:'small', width: 50, height: 50, required: 1}
					],
				},
				newResolution : {code:'', width: 0, height: 0},
				errors: {},
				errorPath: false,
			}
		},
		methods:
		{
			/**
			 * Cancel editing settgins
			 */
			cancel()
			{
				this.$emit('cancel');
			},

			/**
			 * Save settings
			 */
			save()
			{
				var error = false;

				if(!this.validate())
					return;

				this.$emit('save', this.localSettings);
			},
			/**
			 * Validate settings
			 */
			validate()
			{
				var error = false;

				for(var index in this.localSettings)
				{
					if(this.localSettings[index] != false && this.localSettings[index] != '')
						continue;

					this.$set(this.errors, index, {message: 'Field is required'})
					error = true;
				}

				if(this.errorPath !== false)
				{
					this.$set(this.errors, 'savePath', {message: this.errorPath})
					error = true;
				}

				var resolutionsErrors = [];

				for(var index in this.localSettings.resolutions)
				{
					var resolution = this.localSettings.resolutions[index];

					if(resolution.code != '' && (resolution.width > 0 || resolution.height > 0))
						continue;

					if(resolution.code == '')
						resolutionsErrors[index] = {message: "Code can't be empty"};
					else if(!this.checkCodeUnique(resolution.code, index))
						resolutionsErrors[index] = {message: "Code must be unique"};
					else
						resolutionsErrors[index] = {message: "Height or width must be filled"};
				}

				if(resolutionsErrors.length == 0)
					return !error;

				error = true;
				this.$set(this.errors, 'resolutions', resolutionsErrors);

				return !error;
			},

			/**
			 * check unique code
			 */
			checkCodeUnique(code, codeIndex)
			{
				for(var index in this.localSettings.resolutions)
					if(index != codeIndex && this.localSettings.resolutions[index].code == code)
						return false;

				return true;
			},

			/**
			 * check path
			 */
			async checkPath()
			{
				if(this.localSettings.savePath == '')
					return;

				var qs   = require('qs'),
					data = qs.stringify({
						path: this.localSettings.savePath
					});

				var result = await this.$axios({
					method: 'POST',
					url: '/field/em_file/index/checkPath/',
					data: data
				});

				if(result.data.success == true)
				{
					this.errorPath = false;
					return;
				}

				this.errorPath = result.data.message;
			},

			/**
			 * remove resolution by index
			 */
			removeResolution(index)
			{
				this.$delete(this.localSettings.resolutions, index);
				this.errors = {};
			},

			/**
			 * add new resolution
			 */
			addResolution()
			{
				this.localSettings.resolutions.push(Object.assign({}, this.newResolution));
			},
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			for(var index in this.localSettings)
			{
				if(typeof this.settings[index] == 'undefined')
					continue;

				this.$set(this.localSettings, index, this.settings[index])
			}
		}
	}
</script>
<style lang="scss">
	.em-file__settings-path
	{
		display:flex;
		span
		{
			background: #F0F1F3;
			font-size:12px;
			line-height: 28px;
			height: 28px;
			padding:0 10px;
			border-radius: 2px 0 0 2px;
			color: #191C21;
			max-width: 180px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		input
		{
			border:1px solid #F0F1F3;
			border-radius: 0 2px 2px 0;
			font-size:12px;
			height: 28px;
			padding:0 10px;
		}
	}
	.em-file__s-resolutions{margin-bottom: 20px;}
	.em-file__s-resolution
	{
		height: 40px;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		display: flex;
		align-items: center;
		&:first-child{border-top: 1px solid rgba(103, 115, 135, 0.1);}
		position: relative;
	}
	.em-file__s-code,.em-file__s-width,.em-file__s-height
	{
		width:180px;
		border-right:1px solid rgba(103, 115, 135, 0.1);
		height: 100%;
		padding-left:10px;
		line-height:40px;
		color:#677387;
		font-size: 12px;
		flex-shrink: 0;
	}
	.em-file__s-width,.em-file__s-height{width:140px;}
	.em-file__s-remove{padding-left:10px;}
	.em-file__s-remove-button
	{
		width: 24px;
		height: 24px;
		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 2px;
		cursor: pointer;
		&:hover {background-color: rgba(103, 115, 135, 0.1); }
		svg{stroke:#677387;transform: rotate(45deg);}
	}
	.em-file__s-error
	{
		position: absolute;
		top:0px;
		left:10px;
		color: rgba(208, 18, 70, 0.4);
		font-size: 8px;
		line-height: 11px;
	}
	@media (max-width: 768px)
	{
		.em-file__s-code,
		.em-file__s-width,
		.em-file__s-height
		{
			width: auto;
			min-width: 100px;
			flex-basis: 33.4%;
		}
		.em-file__settings-path
		{
			margin-top: 8px;
			width: 100%;
			span,
			input
			{
				max-width: fit-content;
				flex-basis: 50%;
				width: 50%;
			}
			span
			{
				overflow-x: scroll;
				&::webkit-scrollbar { width: 0; height: 0; }
			}
		}
		.em-file__s-remove { padding-left:5px; }
		.em-file__s-remove-button { width: 12px; height: 12px; }
	}
</style>
