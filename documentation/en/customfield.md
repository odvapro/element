# Custom Field
To create your own field that will not be deleted on system update , you need to
Create the following architecture
```
/app/fields/
|- /custom_example
	|- /controllers/ (optional)
		|- IndexFController.php (optional)
		|- ... (oterh controllers optional)
	|- CustomExampleField.php
	|- Field.js
	|- Settings.js
	|- style.css
	|- info.json
```

- CustomExampleField.php - the back part of the field describes - the events of saving and receiving field data
- Field.js - Vue component
- Settings.js - Vue component for settings
- style.css - all styles
- info.json - information about field

### CustomExampleField.php
Custom back part example
```
<?php

class CustomExampleField extends FieldBase
{
	protected $fieldValue = '';

	/**
	 * Get gield value
	 */
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));
	}

	/**
	 * Save field value
	 */
	public function saveValue()
	{
		if(empty($this->fieldValue) || strtotime($this->fieldValue) === false)
			return NULL;
		return $this->fieldValue;
	}
}
```

### Field.js
Custom Vue Component Field Example
```
Vue.component('custom_example', {
	template: `<div class="em-string">
					<template v-if="mode == 'edit'">
						<input
							type="text"
							class="el-inp-noborder"
							@change="changeValue"
							:value="fieldValue"
							placeholder="Empty"
						/>
					</template>
					<template v-else>
						{{ fieldValue }}
						<span v-if="!fieldValue" class="el-empty">Empty</span>
					</template>
				</div>`,
	props: ['fieldValue','fieldSettings','mode', 'view'],
	data()
	{
		return {
		}
	},
	methods:
	{
		/**
		 * Send change current value
		 */
		changeValue(event)
		{
			this.$emit('onChange', {
				value     : event.target.value,
				settings  : this.fieldSettings
			});
		}
	}
});
```

### Settings.js
Custom field settings example field

```
Vue.component('custom_date', {
	template: `<div class="em-list">
					<div class="em-list__line">
						<div class="em-list__key-block">Key</div>
						<div class="em-list__key-block">Value</div>
					</div>
					<div
						class="em-list__line"
						v-for="listItem, index in localSettings.list"
						:key="index"
					>
						<div class="em-list__key-block">
							<input
								type="text"
								class="el-inp-noborder"
								v-model="listItem.key"
								placeholder="Enter key"
							/>
						</div>
						<div class="em-list__value-block">
							<input
								type="text"
								class="el-inp-noborder"
								v-model="listItem.value"
								placeholder="Enter value"
							/>
						</div>
						<div class="em-list__remove-block">
							<div class="em-list__remove-item" @click.stop="removeValue(index)">
								<svg width="12" height="12">
									<use xlink:href="#plus-white"></use>
								</svg>
							</div>
						</div>
					</div>
					<div class="em-list__add-line">
						<button class="el-gbtn" @click="addValues()">Add option</button>
					</div>
					<div class="popup__buttons">
						<button @click="cancel()" class="el-gbtn">Cancel</button>
						<button @click="save()" class="el-btn">Save settigns</button>
					</div>
			   </div>`,
	props: ['settings'],
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
		 * Remove field value
		 */
		removeValue(fieldIndex)
		{
			this.localSettings.list.splice(fieldIndex, 1);
		},
		/**
		 * Add values to the field value list
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
	mounted()
	{
		for(var index in this.localSettings)
		{
			if(typeof this.settings[index] == 'undefined')
				continue;

			this.$set(this.localSettings, index, this.settings[index])
		}
	}
});
```

### info.json
Custom json file example

```
{
	"name": "Custom Example",
	"iconPath": "/images/date.svg",
	"type": "custom"
}
```

### Controllers
Controllers are available by the following path
`/element/fld/<feld_code>/<field_controller_name>/<action>/`