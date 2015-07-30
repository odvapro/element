<div id="TPLS" style="display:none;">
	<div class="imageSizeTPL">
		<div class="editLine">
			<div class="inp imageSize">
				<input class="imsName" name="set[imageSizes][#key#][name]" type="text" placeholder="name"/>
				<input class="imsFix" name="set[imageSizes][#key#][fixed]" type="hidden" value="0" />
				<input class="imsFix" name="set[imageSizes][#key#][fixed]" value="1" type="checkbox" checked="true"/>
				<input class="imsWidth" name="set[imageSizes][#key#][width]" type="text" placeholder="ширина"  />
				<input class="imsHeight" name="set[imageSizes][#key#][height]" type="text" placeholder="высота" />
				<span onclick="el.settings.file.removeImageSizeLine(this)" class="icon deleteBtn"></span>
			</div>
		</div>
	</div>
</div>