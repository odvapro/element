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
				<Select :defaultText="selectedNodeField">
					<SelectOption
						v-for="field,fieldIndex in fields"
						:key="fieldIndex"
						@click.native="selectNodeField(field)"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				Search field
				<small class="popup__field-error">example</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedSearchField">
					<SelectOption
						v-for="field,fieldIndex in fields"
						:key="fieldIndex"
						@click.native="selectSearchField(field)"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
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
				required      : false,
				tables        : [],
				fields        : [],
				localSettings : {
					nodeTableCode  : false,
					nodeFieldCode  : false,
					nodeSearchCode : false,
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
				var table = false;

				if(this.localSettings.nodeTableCode !== false)
					table = this.getTableByCode(this.localSettings.nodeTableCode);;

				if(table === false)
					return 'Select table'

				return table.name;

			},
			/**
			 * Default text on select table
			 */
			selectedNodeField()
			{
				var field = false;

				if(this.localSettings.nodeFieldCode !== false)
					field = this.getFieldByCode(this.localSettings.nodeFieldCode);

				if(field === false)
					return 'Select field'

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedSearchField()
			{
				var field = false;

				if(this.localSettings.nodeSearchCode !== false)
					field = this.getFieldByCode(this.localSettings.nodeSearchCode);

				if(field === false)
					return 'Select field'

				return (field.em.name) ? field.em.name : field.field;
			},
		},
		methods:
		{
			/**
			 * Get table by code
			 */
			getTableByCode(code)
			{
				for(var index in this.tables)
				{
					if(this.tables[index].code != code)
						continue;

					return this.tables[index];
				}

				return false;
			},
			/**
			 * Get field by code
			 */
			getFieldByCode(code)
			{
				for(var index in this.fields)
				{
					if(this.fields[index].field != code)
						continue;

					return this.fields[index];
				}

				return false;
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
				this.$emit('save', this.localSettings);
			},

			/**
			 * Select node table
			 */
			selectTable(table)
			{
				if(this.localSettings.nodeTableCode == table.code)
					return;

				this.localSettings.nodeTableCode  = table.code;
				this.localSettings.nodeFieldCode  = false;
				this.localSettings.nodeSearchCode = false;
				this.fields = table.columns;
			},

			/**
			 * Select node field
			 */
			selectNodeField(field)
			{
				this.localSettings.nodeFieldCode = field.field;
			},

			/**
			 * Select node search field
			 */
			selectSearchField(field)
			{
				this.localSettings.nodeSearchCode = field.field;
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

			if(typeof this.settings.nodeTableCode != 'undefined')
			{
				var table = this.getTableByCode(this.settings.nodeTableCode);

				if(!table)
					return;

				this.selectTable(table);
			}

			for(var index in this.localSettings)
			{
				if(typeof this.settings[index] == 'undefined')
					continue;

				this.$set(this.localSettings, index, this.settings[index])
			}
		}
	}
</script>