<template>
	<div class="settings-popup-row-params">
		<div class="popup__field" v-for="listItem, index in list" :key="index">
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
				required: false,
				list: [
					{key: '', value: ''}
				]
			}
		},
		methods:
		{
			/**
			 * Удалить значение поля
			 */
			removeValue(fieldIndex)
			{
				this.list.splice(fieldIndex, 1);
			},
			/**
			 * Добавить значения в список значений филда
			 */
			addValues()
			{
				this.list.push({key: '', value: ''});
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
					required: this.required,
					list: this.list
				};

				this.$emit('save', formData);
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.required = this.isRequired;

			if (typeof this.settings.list != 'undefined')
				this.list = this.settings.list;
		}
	}
</script>