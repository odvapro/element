<template>
	<div class="settings-popup-row-params">
		<div class="popup__field" v-for="listItem, index in localSettings.list" :key="index">
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" v-model="listItem.key" placeholder="Key">
			</div>
			<div class="popup__field-input">
				<input type="text" class="el-inp-noborder" v-model="listItem.value" placeholder="Value">
			</div>
			<div class="popup__field-input">
				<div @click="removeValue(index)">remove field</div>
			</div>
		</div>
		<div class="popup__field-input">
			<div @click="addValues()">add field</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">Cancel</button>
			<button @click="save()" class="el-btn">Save settigns</button>
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
					list: [
						{key: '', value: ''}
					]
				},
			}
		},
		methods:
		{
			/**
			 * Удалить значение поля
			 */
			removeValue(fieldIndex)
			{
				this.localSettings.list.splice(fieldIndex, 1);
			},
			/**
			 * Добавить значения в список значений филда
			 */
			addValues()
			{
				this.localSettings.list.push({key: '', value: ''});
			},
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
				let formData = {
					list: this.localSettings.list
				};

				this.$emit('save', formData);
			}
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