##Компонент принимает 3 свойства

###fieldValue
значение из БД

###fieldSettings
{
	primaryKey:{fieldCode:<код филда>,value:<значение>}
	fieldCode
	tableCode
	...  (остальые поля для каждого филда свои)
}

###mode
- edit
- read
- editWithoutSave

### по изменению значения в филде отрабатывает следующий emit
this.$emit('onChange', {
	value    : <значени в бд>,
	settings : <основной формат настроек>
});


## Форма настроект филда
- закрытие формы
this.$emit('cancel');
- coхранение формы
this.$emit('save',{})

Пример поля
```
<div class="popup__field">
	<div class="popup__field-name">
		Required
		<small class="popup__field-error">example</small>
	</div>
	<div class="popup__field-input">
		<input type="text" class="el-inp-noborder" placeholder="Enter email" v-model="required">
	</div>
</div>
```
