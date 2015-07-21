{% extends "editFields/em_string.volt" %}
{% block input %}
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
		<div class="attaches">
			{% if element[fieldArr['field']] is defined %}
				{% for aFile in element[fieldArr['field']] %}
					<div class="attach" title="{{aFile['upName']}}">
						<span onclick="el.edit.removeFileAttach(this);" class="delete icon deleteBtn"></span>
						{% if aFile['type'] == "image" %}
							<div class="atIcon"><img src="/{{aFile['sizes']['small']}}" alt="{{aFile['upName']}}"></div>
						{% else %}
							<div class="atIcon"><img src="{{baseUri}}img/fileIcon.png" alt="{{aFile['upName']}}"></div>
						{% endif %}
						<input type="hidden" name="field[{{fieldArr['field']}}][{{aFile['index']}}][jsonFileObj]" value="{{aFile['jsonFileObj']}}" />
					</div>
				{% endfor %}
			{% endif %}
			<button class="attachAdd" onclick="el.edit.getFileUploadForm(this,'{{fieldArr['field']}}');" ><span class="icon addBtn"></span></button>
		</div>
	</div>
{% endblock %}