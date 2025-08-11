<template>
	<div class="settings-popup-row-params">
		<div class="popup__field">
			<div class="popup__field-name">Multiple</div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.multiple"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">Disabled <span class="em_field--beta">(beta)</span></div>
			<div class="popup__field-input">
				<Checkbox
					:checked.sync="localSettings.disabled"
				></Checkbox>
			</div>
		</div>
		<div class="popup__field">
			<div class="popup__field-name">Filter <span class="em_field--beta">(beta)</span></div>
			<div class="popup__field-input popup__field-input--filter">
				<Checkbox
					@change="updateFilter()"
					:checked="localSettings.useFilter"
				></Checkbox>
				<Select
					v-if="localSettings.useFilter"
					:defaultText="localSettings.filters.field !== 'false' ? localSettings.filters.field : 'Field'"
				>
					<SelectOption
						v-if="localSettings.useFilter && filterFields"
						v-for="field,fieldIndex in filterFields"
						:key="fieldIndex"
						@click.native="selectFilterField(field)"
					>{{ field[1].field }}</SelectOption>
				</Select>
				<Select
					v-if="localSettings.useFilter"
					:defaultText="localSettings.filters.value !== 'false' ? localSettings.filters.value.name : 'Value'"
					:disabled="!filterValues"
				>
					<SelectOption
						v-if="fields"
						v-for="value,valueIndex in filterValues"
						:key="valueIndex"
						@click.native="selectFilterValue(value)"
					>{{ value.name }}</SelectOption>
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
					multiple  : false,
					disabled  : false,
					useFilter: false,
					nodeTableCode  : false,
					nodeFieldCode  : false,
					nodeSearchCode : false,
					field_code: false,
					filters: {
						field: false,
						value: false,
					},
				},
				filterValues: false,
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
			filterFields()
			{
				const columns = this.$store.getters.getColumns(this.localSettings.nodeTableCode);
				const fields = [];

				for (let item of Object.entries(columns))
				{
					if (item[1].em.type != 'em_node')
						continue;

					fields.push(item);
				}

				return fields;
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
					if(this.localSettings[index] != false || index == 'multiple' || index == 'disabled' || index == 'useFilter' || index == 'filters')
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
			async loadFilterValues()
			{
				if (!this.localSettings.filters)
					return;

				let result = await this.$axios({
					method : 'GET',
					headers: { 'Content-Type': 'multipart/form-data' },
					url    : `/field/em_node/index/getFieldValues/?table=${this.localSettings.nodeTableCode}&column=${this.localSettings.filters.field}`,
				});

				if (!result.data.success)
					return false;

				this.filterValues = result.data.result;
			},
			selectFilterField(field)
			{
				this.localSettings.filters.field = field[1].field;
				this.loadFilterValues();
			},
			selectFilterValue(value)
			{
				this.localSettings.filters.value = value;
			},
			updateFilter()
			{
				this.localSettings.useFilter = !this.localSettings.useFilter;

				if (!this.localSettings.useFilter)
					this.localSettings.filters = {
						field: false,
						value: false,
					};

				return true;
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

				if (this.settings[index] === 'false' || this.settings[index] === 'true')
					this.$set(this.localSettings, index, (this.settings[index] === 'true'));
				else
					this.$set(this.localSettings, index, this.settings[index])
			}

			this.loadFilterValues();
		}
	}
</script>

<style lang="scss">
	.popup__field-input--filter
	{
		display: flex;
		align-items: center;
		gap: 10px;
	}
</style>