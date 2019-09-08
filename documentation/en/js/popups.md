# Попапы

Глобально доступен компонент Popup

свойства
visible - показать попап
<slot> - содержимое попапа


### Пример использования
```
<Popup :visible.sync="settingsPopup">
	<div class="popup__name">Settings</div>
	<component
		:is="settingsComponent"
		:settings="currentSettgins"
		@save="saveSettings"
		@cancel="settingsPopup = false"
	></component>
</Popup>
```