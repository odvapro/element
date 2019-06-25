##Компонент принимает 4 свойства

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

###view
-table
-detail
Код вьюъи в которо отображается компонет - для изменения внешнего вида на рзных отоброженях

### по изменению значения в филде отрабатывает следующий emit
this.$emit('onChange', {
	value    : <значени в бд>,
	settings : <основной формат настроек>
});
#### <основной формат настроек>
settings = {
	primaryKey{
		value     : <значение ключа>,
		fieldCode : <поле ключа>
	}
	fieldCode  = <код филда/колонки>;
	tableCode  = <код таблицы>
	...
	другие кастомные параметры филда
}



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
