<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				Folder for save
				<small v-if="errors.savePath" class="popup__field-error">{{ errors.savePath.message }}</small>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{ settings.rootPath }}
			</div>
			<div class="popup__field-input">
				<input
					type="text"
					class="el-inp-noborder"
					@change="checkPath"
					v-model="localSettings.savePath"
					placeholder="Folder for save"
				/>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Resolutions
				<small v-if="errors.nodeFieldCode" class="popup__field-error">{{ errors.nodeFieldCode.message }}</small>
			</div>
		</div>
		<div class="popup__field" v-for="listItem, index in localSettings.resolutions" :key="index">
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" v-model="listItem.code" placeholder="Code" :disabled="listItem.required">
			</div>
			<div class="popup__field-input">
				<input type="number" class="el-inp-noborder" v-model.number="listItem.width" placeholder="Width">
			</div>
			<div class="popup__field-input">
				<input type="number" class="el-inp-noborder" v-model.number="listItem.height" placeholder="height">
			</div>
			<div class="popup__field-input" v-if="!listItem.required">
				<div @click="removeResolution(index)">remove resolution</div>
			</div>
			<small v-if="errors.resolutions && errors.resolutions[index]" class="popup__field-error">{{ errors.resolutions[index].message }}</small>
		</div>
		<div class="popup__field-input">
			<div @click="addResolution()">add resolution</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">Cancel</button>
			<button @click="save()" class="el-btn">Save settigns</button>
		</div>
	</div>
</template>
<script>
	import Select from '@/components/forms/Select.vue';
	import SelectOption from '@/components/forms/SelectOption.vue';
	export default
	{
		props: ['settings'],
		components:{Select,SelectOption},
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
		computed:
		{
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