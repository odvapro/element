<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmMatrix.settings.key_field')}}
				<small v-if="errors.keyField" class="popup__field-error">{{ errors.keyField.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedKeyField">
					<SelectOption
						v-for="field,fieldKey in currentTable.columns"
						:key="fieldKey"
						@click.native="selectKeyField(field)"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('table')}}
				<small v-if="errors.nodeTableCode" class="popup__field-error">{{ errors.nodeTableCode.message }}</small>
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
				{{$t('fieldEmMatrix.settings.node_table')}}
				<small v-if="errors.nodeField" class="popup__field-error">{{ errors.nodeField.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedSearchField" :disabled="(fields === false)">
					<SelectOption
						v-if="fields"
						v-for="field,fieldIndex in fields"
						:key="fieldIndex"
						@click.native="selectSearchField(field)"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__buttons">
			<button @click="cancel()" class="el-gbtn">{{$t('cancel')}}</button>
			<button @click="save()" class="el-btn">{{$t('save_settings')}}</button>
		</div>
	</div>
</template>
<script>
	export default
	{
		props: ['settings', 'currentTable'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				localSettings :
				{
					nodeTableCode  : false,
					keyField  : false,
					nodeField : false,
				},
				errors: {}
			}
		},
		computed:
		{
			/**
			 * Get tables list
			 */
			tables()
			{
				return this.$store.state.tables.tables;
			},
			/**
			 * Get fields list for current table
			 */
			fields()
			{
				return this.$store.getters.getColumns(this.localSettings.nodeTableCode);
			},
			/**
			 * Default text on select table
			 */
			selectedTable()
			{
				var table = false;

				if(this.localSettings.nodeTableCode !== false)
					table = this.$store.getters.getTable(this.localSettings.nodeTableCode)

				if(table === false)
					return this.$t('fieldEmMatrix.settings.select_table');

				return table.name;
			},
			/**
			 * Default text on select table
			 */
			selectedKeyField()
			{
				var field = false;

				if(this.localSettings.keyField !== false)
					field = this.$store.getters.getColumn(this.currentTable.code, this.localSettings.keyField)

				if(field === false)
					return this.$t('fieldEmMatrix.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedSearchField()
			{
				var field = false;

				if(this.localSettings.nodeField !== false)
					field = this.$store.getters.getColumn(this.localSettings.nodeTableCode, this.localSettings.nodeField)

				if(field === false)
					return this.$t('fieldEmMatrix.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
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

				for(var index in this.localSettings)
				{
					if(this.localSettings[index] != false)
						continue;

					this.$set(this.errors, index, {message: 'Field is required'})
					error = true;
				}

				if(error)
					return;

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
				this.localSettings.nodeField = false;
				this.$delete(this.errors, 'nodeTableCode');
			},

			/**
			 * Select node field
			 */
			selectKeyField(field)
			{
				this.localSettings.keyField = field.field;
				this.$delete(this.errors, 'keyField');
			},

			/**
			 * Select node search field
			 */
			selectSearchField(field)
			{
				this.localSettings.nodeField = field.field;
				this.$delete(this.errors, 'nodeField');
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