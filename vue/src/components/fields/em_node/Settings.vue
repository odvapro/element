<template>
	<div class="settings-popup-row-params">
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
				{{$t('fieldEmNode.settings.node_field')}}
				<small v-if="errors.nodeFieldCode" class="popup__field-error">{{ errors.nodeFieldCode.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedNodeField" :disabled="(fields === false)">
					<SelectOption
						v-if="fields"
						v-for="field,fieldIndex in fields"
						:key="fieldIndex"
						@click.native="selectNodeField(field)"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmNode.settings.search_field')}}
				<small v-if="errors.nodeSearchCode" class="popup__field-error">{{ errors.nodeSearchCode.message }}</small>
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
		props: ['settings'],
		/**
		 * Глобальные переменные странциы
		 */
		data()
		{
			return {
				localSettings :
				{
					nodeTableCode  : false,
					nodeFieldCode  : false,
					nodeSearchCode : false,
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
					return this.$t('fieldEmNode.settings.select_table');

				return table.name;

			},
			/**
			 * Default text on select table
			 */
			selectedNodeField()
			{
				var field = false;

				if(this.localSettings.nodeFieldCode !== false)
					field = this.$store.getters.getColumn(this.localSettings.nodeTableCode, this.localSettings.nodeFieldCode)

				if(field === false)
					return this.$t('fieldEmNode.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedSearchField()
			{
				var field = false;

				if(this.localSettings.nodeSearchCode !== false)
					field = this.$store.getters.getColumn(this.localSettings.nodeTableCode, this.localSettings.nodeSearchCode)

				if(field === false)
					return this.$t('fieldEmNode.settings.select_field');

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
				this.localSettings.nodeFieldCode  = false;
				this.localSettings.nodeSearchCode = false;
				this.$delete(this.errors, 'nodeTableCode');
			},

			/**
			 * Select node field
			 */
			selectNodeField(field)
			{
				this.localSettings.nodeFieldCode = field.field;
				this.$delete(this.errors, 'nodeFieldCode');
			},

			/**
			 * Select node search field
			 */
			selectSearchField(field)
			{
				this.localSettings.nodeSearchCode = field.field;
				this.$delete(this.errors, 'nodeSearchCode');
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