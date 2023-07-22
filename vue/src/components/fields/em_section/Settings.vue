	<template>
		<div class="settings-popup-row-params">
			<div class="popup__field">
				<div class="popup__field-name">
					Sections table
					<small v-if="errors.sectionTableCode" class="popup__field-error">{{ errors.sectionTableCode.message }}</small>
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
					<small v-if="errors.sectionFieldCode" class="popup__field-error">{{ errors.sectionFieldCode.message }}</small>
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
					Search field
					<small v-if="errors.sectionSearchCode" class="popup__field-error">{{ errors.sectionSearchCode.message }}</small>
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
			<div class="popup__field">
				<div class="popup__field-name">
					Parents field
					<small v-if="errors.sectionParentsFieldCode" class="popup__field-error">{{ errors.sectionParentsFieldCode.message }}</small>
				</div>
				<div class="popup__field-input">
					<Select :defaultText="selectedParentsField" :disabled="(fields === false)">
						<SelectOption
							v-if="fields"
							v-for="field,fieldIndex in fields"
							:key="fieldIndex"
							@click.native="selectParentsField(field)"
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
						sectionTableCode  : false,
						sectionFieldCode  : false,
						sectionSearchCode : false,
						sectionParentsFieldCode : false,
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
					return this.$store.getters.getColumns(this.localSettings.sectionTableCode);
				},
				/**
				 * Default text on select table
				 */
				selectedTable()
				{
					var table = false;

					if(this.localSettings.sectionTableCode !== false)
						table = this.$store.getters.getTable(this.localSettings.sectionTableCode)

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

					if(this.localSettings.sectionFieldCode !== false)
						field = this.$store.getters.getColumn(this.localSettings.sectionTableCode, this.localSettings.sectionFieldCode)

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

					if(this.localSettings.sectionSearchCode !== false)
						field = this.$store.getters.getColumn(this.localSettings.sectionTableCode, this.localSettings.sectionSearchCode)

					if(field === false)
						return this.$t('fieldEmNode.settings.select_field');

					return (field.em.name) ? field.em.name : field.field;
				},

				/**
				 * Default text on select parents field
				 */
				selectedParentsField()
				{
					var field = false;

					if(this.localSettings.sectionParentsFieldCode !== false)
						field = this.$store.getters.getColumn(this.localSettings.sectionTableCode, this.localSettings.sectionParentsFieldCode)

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
						if(this.localSettings[index] != false || index == 'multiple')
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
					if(this.localSettings.sectionTableCode == table.code)
						return;

					this.localSettings.sectionTableCode  = table.code;
					this.localSettings.sectionFieldCode  = false;
					this.localSettings.sectionSearchCode = false;
					this.$delete(this.errors, 'sectionTableCode');
				},

				/**
				 * Select node field
				 */
				selectNodeField(field)
				{
					this.localSettings.sectionFieldCode = field.field;
					this.$delete(this.errors, 'sectionFieldCode');
				},

				/**
				 * Select node search field
				 */
				selectSearchField(field)
				{
					this.localSettings.sectionSearchCode = field.field;
					this.$delete(this.errors, 'sectionSearchCode');
				},

				/**
				 * Select node search field
				 */
				selectParentsField(field)
				{
					this.localSettings.sectionParentsFieldCode = field.field;
					this.$delete(this.errors, 'sectionParentsFieldCode');
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

					if (this.settings[index] === 'false' || this.settings[index] === 'true')
						this.$set(this.localSettings, index, (this.settings[index] === 'true'));
					else
						this.$set(this.localSettings, index, this.settings[index])
				}
			}
		}
	</script>