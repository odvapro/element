# Fields
You do not have to write any code to work with standard fields, you just need to specify a match for a column and field in the settings

* [Setting](#setting)
* [Field structure](#field-structure)
* [Field frontend](#field-frontend)

## Setting
- Go to settings page `/element/settings`
- Open corresponding table
- In the list, change the field type in the corresponding column
- If the field has settings, you will see a "Settings" button on the right. After clicking on it you will see the field settings, which can be different for each field

## Field structure
All fields are located in `/app/fields/` folder, each within its own folder. And they have the following architecture
```
/controllers/		# Folder with controllers, optional folder
/models/			# Folder with models, optional folder
EmCheckField.php 	# Major file, processing all field events
info.json 			# Name
```

The frontend for the standard fields is kept in the folder `/vue/src/components/fields/`

- Field.vue — the field
- Settings.vue — field settings form

## Field frontend
The Field.vue component receives 4 attributes: `fieldValue`, `fieldSettings`, `mode`, and `view`

### fieldValue
Current value

### fieldSettings
Field settings: a compound array of mandatory attributes and settings from the settings form
```
{
	primaryKey{
		value     : <key value>,
		fieldCode : <key field>
	}
	fieldCode  = <code of the field/column>;
	tableCode  = <table code>
	...
	other customized parameters for the field
}
```

### mode
Field operation mode is editing/reading/editing with immediate saving
- `edit`
- `read`
- `editWithoutSave`

###view
The display code which returns the field now has only two options
- `table` table display
- `detail` detailed element page