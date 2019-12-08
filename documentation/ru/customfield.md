# Кстомизированный филд
Для создания своего филда который не будет удаляться при обновлении системы нужно
Создать следующую архитектуру
```
/app/fields/
|- /custom_example
	|- /controllers/ (опционально)
		|- IndexFController.php (опционально)
		|- ... (необходимые контроллеры опционально)
	|- CustomExampleField.php
	|- Field.js
	|- Settings.js
	|- style.css
	|- info.json
```

- CustomExampleField.php - бэк часть филда описывает события сохраниени и получение данных филда
- Field.js - Vue компонент
- Settings.js - Vue компонент для настроек
- style.css - все стили кастомизированного филда
- info.json - ифнормация о филде

# Ссылка на скачиваени архива


### CustomExampleField.php
Пример бэка кастомизированного филда
```
<?php

class CustomExampleField extends FieldBase
{
	protected $fieldValue = '';

	/**
	 * Достать значение поля
	 */
	public function getValue()
	{
		return strval(strip_tags($this->fieldValue));
	}

	/**
	 * Сохранить значение
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
Пример Vue компонента кастомизированного филда
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
Пример филда настроек кастомизированного филда

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
});
```

### info.json
Пример кастомизированного json файла

```
{
	"name": "Custom Example",
	"iconPath": "/images/date.svg",
	"type": "custom"
}
```

### Контроллеры
Контроллеры доступны по следующему пути
`/element/fld/<feld_code>/<field_controller_name>/<action>/`

И ваши vue компоненты могут отправлять на них запросы