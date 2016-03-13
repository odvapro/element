{% extends "table/stringEditField.volt" %}
{% block input %}
	<div class="filedEdit" data-fieldName="{{fieldArr['field']}}" data-multiple="{{fieldArr['multiple']}}">
		<div class="attaches">
			{% if element[fieldArr['field']] is defined and element[fieldArr['field']] != '' %}
				{% for aFile in element[fieldArr['field']] %}
					<div class="attach" title="{{aFile['upName']}}">
						<span onclick="el.edit.removeFileAttach(this);" class="delete icon deleteBtn"></span>
						{% if aFile['type'] == "image" %}
							<div class="atIcon"><img src="{{aFile['sizes']['small']}}" alt="{{aFile['upName']}}"></div>
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
{% block TPLS %}
	<div id="TPLS" style="display:none;">
		<div class="fileTPL">
			<div class="attach" title="#name#">
				<span onclick="el.edit.removeFileAttach(this);" class="delete icon deleteBtn"></span>
				<div class="atIcon"><img src="#icon#" alt="#name#" /></div>
				<input type="hidden" name="field[#fieldName#][#index#][jsonFileObj]" value="#value#" />
				<input type="hidden" class="tmpfield deleteaftersave" name="field[#fieldName#][#index#][tmp]" value="1" />
			</div>
		</div>
		<div class="nodeTPL">
			<div class="node">
				<span class="delete icon deleteBtn" onclick="el.edit.removeNode(this);"></span>
				<div class="noIcon">#searchValue#</div>
				<input type="hidden" name="field[#fieldName#][]" value="#value#" />
			</div>
		</div>
	</div>
{% endblock %}