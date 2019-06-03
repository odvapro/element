<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				Table
				<small class="popup__field-error">example</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedTable">
					<SelectOption
						v-for="table,tableIndex in tables"
						:key="tableIndex"
						@click.native="selectTable(table)"
					>{{ table.name }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Node field
				<small class="popup__field-error">example</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedTable">
					<SelectOption
						v-for="field,fieldIndex in fields"
						:key="fieldIndex"
						@click.native="selectNodeField(field)"
					>{{ field.code }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Search field
				<small class="popup__field-error">example</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedTable">
					<SelectOption
						v-for="table,tableIndex in tables"
						:key="tableIndex"
						@click.native="selectTable(table.code)"
					>{{ table.name }}</SelectOption>
				</Select>
			</div>
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
		props: ['settings','isRequired'],
		components:{Select,SelectOption},
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				required: false,
				tables:[],
				fields:[],
				localSettings:{
					tableCode:false,
					nodeFieldCode:false
				}
			}
		},
		computed:
		{
			/**
			 * Default text on select table
			 */
			selectedTable()
			{
				if(this.localSettings.tableCode === false)
					return 'Select table'
				else
					return this.localSettings.tableCode
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
				this.$emit('save',{})
			},

			selectTable(table)
			{
				this.localSettings.tableCode = table.tableCode;
				this.fields = table.columns;
			},

			selectNodeField(field)
			{
				// this.localSettings.tableCode = table.tableCode;
			},

			/**
			 * Задать обязательность поля
			 */
			setStatus(status)
			{
				this.required = status;
				this.$emit('changeSettings', {required: status});
			}
		},
		/**
		 * Хук при загрузке страницы
		 */
		mounted()
		{
			this.required = this.isRequired;
			this.setStatus(this.required);

			this.tables = this.$store.state.tables.tables;
		}
	}
</script>