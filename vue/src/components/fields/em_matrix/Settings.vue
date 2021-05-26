<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmMatrix.settings.many_to_many')}}
				<small v-if="errors.isManyToMany" class="popup__field-error">{{ errors.isManyToMany.message }}</small>
			</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.isManyToMany"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmMatrix.settings.key_field')}}
				<small v-if="errors.localField" class="popup__field-error">{{ errors.localField.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedLocalField">
					<SelectOption
						v-for="field,fieldKey in currentTable.columns"
						:key="fieldKey"
						@click.native="selectField(field, 'local')"
					>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
				</Select>
			</div>
		</div>
		<template v-if="localSettings.isManyToMany">
			<div class="popup__field">
				<div class="popup__field-name">
					{{$t('fieldEmMatrix.settings.node_table')}}
					<small v-if="errors.nodeTableCode" class="popup__field-error">{{ errors.nodeTableCode.message }}</small>
				</div>
				<div class="popup__field-input">
					<Select :defaultText="selectedNodeTable">
						<SelectOption
							v-for="table,tableIndex in tables"
							:key="tableIndex"
							@click.native="selectTable(table, 'nodeTable')"
						>{{ table.name }}</SelectOption>
					</Select>
				</div>
			</div>
			<div class="popup__field">
				<div class="popup__field-name">
					{{$t('fieldEmMatrix.settings.key_field')}} <span class="em-matrix--lowercase">({{$t('fieldEmMatrix.settings.node_table')}})</span>
					<small v-if="errors.nodeTableField" class="popup__field-error">{{ errors.nodeTableField.message }}</small>
				</div>
				<div class="popup__field-input">
					<Select :defaultText="selectedNodeTableField" :disabled="nodeTableFields === false">
						<SelectOption
							v-if="nodeTableFields"
							v-for="field,fieldKey in nodeTableFields"
							:key="fieldKey"
							@click.native="selectField(field, 'nodeTable')"
						>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
					</Select>
				</div>
			</div>
			<div class="popup__field">
				<div class="popup__field-name">
					{{$t('fieldEmMatrix.settings.key_field')}} <span class="em-matrix--lowercase">({{$t('fieldEmMatrix.settings.node_table')}} - {{$t('table')}})</span>
					<small v-if="errors.nodeTableFinalTableField" class="popup__field-error">{{ errors.nodeTableFinalTableField.message }}</small>
				</div>
				<div class="popup__field-input">
					<Select :defaultText="selectedNodeTableFinalTableField" :disabled="nodeTableFields === false">
						<SelectOption
							v-if="nodeTableFields"
							v-for="field,fieldKey in nodeTableFields"
							:key="fieldKey"
							@click.native="selectField(field, 'nodeTableFinalTable')"
						>{{ (field.em.name) ? field.em.name : field.field }}</SelectOption>
					</Select>
				</div>
			</div>
		</template>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('table')}}
				<small v-if="errors.finalTableCode" class="popup__field-error">{{ errors.finalTableCode.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedTable">
					<SelectOption
						v-for="table,tableIndex in tables"
						:key="tableIndex"
						@click.native="selectTable(table, 'finalTable')"
					>{{ table.name }}</SelectOption>
				</Select>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">
				{{$t('fieldEmMatrix.settings.select_field')}}
				<small v-if="errors.finalTableField" class="popup__field-error">{{ errors.finalTableField.message }}</small>
			</div>
			<div class="popup__field-input">
				<Select :defaultText="selectedFinalTableField" :disabled="(finalTableFields === false)">
					<SelectOption
						v-if="finalTableFields"
						v-for="field,fieldIndex in finalTableFields"
						:key="fieldIndex"
						@click.native="selectField(field, 'finalTable')"
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
				localSettings:
				{
					/*
					nodeTableCode  : false,
					keyField  : false,
					nodeField : false,
					 */
					isManyToMany             : false,
					localField               : false,

					nodeTableCode            : false,
					nodeTableField           : false,
					nodeTableFinalTableField : false,

					finalTableCode           : false,
					finalTableField          : false,
				},
				errors: {},
			};
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
			finalTableFields()
			{
				return this.$store.getters.getColumns(this.localSettings.finalTableCode);
			},
			/**
			 * Get fields list for node table
			 */
			nodeTableFields()
			{
				return this.$store.getters.getColumns(this.localSettings.nodeTableCode);
			},
			/**
			 * Default text on select table
			 */
			selectedTable()
			{
				var table = false;

				if(this.localSettings.finalTableCode !== false)
					table = this.$store.getters.getTable(this.localSettings.finalTableCode)

				if(table === false)
					return this.$t('fieldEmMatrix.settings.select_table');

				return table.name;
			},
			/**
			 * Default text on select node table
			 */
			selectedNodeTable()
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
			selectedLocalField()
			{
				var field = false;

				if(this.localSettings.localField !== false)
					field = this.$store.getters.getColumn(this.currentTable.code, this.localSettings.localField)

				if(field === false)
					return this.$t('fieldEmMatrix.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedNodeTableField()
			{
				var field = false;

				if(this.localSettings.nodeTableField !== false)
					field = this.$store.getters.getColumn(this.localSettings.nodeTableCode, this.localSettings.nodeTableField)

				if(field === false)
					return this.$t('fieldEmMatrix.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedNodeTableFinalTableField()
			{
				var field = false;

				if(this.localSettings.nodeTableFinalTableField !== false)
					field = this.$store.getters.getColumn(this.localSettings.nodeTableCode, this.localSettings.nodeTableFinalTableField)

				if(field === false)
					return this.$t('fieldEmMatrix.settings.select_field');

				return (field.em.name) ? field.em.name : field.field;
			},
			/**
			 * Default text on select table
			 */
			selectedFinalTableField()
			{
				var field = false;

				if(this.localSettings.finalTableField !== false)
					field = this.$store.getters.getColumn(this.localSettings.finalTableCode, this.localSettings.finalTableField)

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
			 * validate settings data
			 */
			validate()
			{
				let error = false;

				let manyToManyFields = new RegExp('nodeTableCode|nodeTableField|nodeTableFinalTableField');

				for (let key in this.localSettings)
				{

					if (key === 'isManyToMany'
						|| (!this.localSettings.isManyToMany && manyToManyFields.test(key))
						|| this.localSettings[key] !== false
					)
						continue;

					error = true;
					this.$set(this.errors, key, {message: 'Field is required'})
				}

				return !error;
			},
			/**
			 * Save settings
			 */
			save()
			{
				if (this.validate())
					this.$emit('save', this.localSettings);
			},

			/**
			 * Select node table
			 */
			selectTable(table, code='finalTable')
			{
				if (typeof this.localSettings[code+'Code'] === 'undefined' || this.localSettings[code+'Code'] === table.code)
					return;

				this.localSettings[code+'Code'] = table.code;

				if (code === 'nodeTable')
					this.localSettings.nodeTableFinalTableField = false;

				this.localSettings[code+'Field'] = false;
				this.$delete(this.errors, code+'Code');
			},

			/**
			 * Select node field
			 */
			selectField(field, fieldCode='local')
			{
				this.localSettings[fieldCode+'Field'] = field.field;
				this.$delete(this.errors, fieldCode+'Field');
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

				if (this.settings[index] === 'false')
					this.$set(this.localSettings, index, false);
				else
					this.$set(this.localSettings, index, this.settings[index]);
			}
			this.localSettings.isManyToMany = this.localSettings.isManyToMany === 'true';
		},
	};
</script>
<style>
	.em-matrix--lowercase { text-transform: lowercase; }
</style>
