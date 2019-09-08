## List
Принимает параметры
	- selected = массив кодов выбранных опций [keyCode1,keyCode2...]
	- list = массив для выбора [ {key:'exampleKey',value:'exampleValue'},
 								{key:'exampleKey2',value:'exampleValue2'} ]
Пример использования
```
	<List
		:searchText.sync="searchText"
	>
		<template v-slot:selected>
			<ListOption
				v-for="listItem in selectedItems"
				@remove="removeItem(listItem)"
			>{{ listItem.value }}</ListOption>
		</template>
		<ListOption
			v-for="listItem in itemsList"
			@select="selectItem(listItem)"
		>{{ listItem.value }}</ListOption>
	</List>
```


## Select
## Checkbox