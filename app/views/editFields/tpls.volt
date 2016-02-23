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