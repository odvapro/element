<div id="filterLine" style="display:none;">
	<div class="filterBlock">
		<div class="filterBlockWrap _filterWraper">
			<div class="fBlock hideFields" onclick="el.table.filter.openSubPopup(this,event)">
				<div class="ttl"><i class="fa fa-columns" aria-hidden="true"></i> Поля</div>
				<div class="filterPopup _subPoup">
					<div class="filterLines">
						<div class="filterLine">
							<span class="fName">Список полей</span>	
						</div>
					</div>
					<span onclick="el.table.filter.closeSubPopup(event)" class="closeButton"><i class="fa fa-times" aria-hidden="true"></i></span>
				</div>
			</div>
			<div class="fBlock filter" onclick="el.table.filter.openSubPopup(this,event)">
				<div class="ttl"><i class="fa fa-filter" aria-hidden="true"></i> Фильтр</div>
				<div class="filterPopup _subPoup">
					<div class="filterLines">
						<div class="filterLine">
							<span class="fName">Список фильтров</span>
						</div>
					</div>
					<span onclick="el.table.filter.closeSubPopup(event)" class="closeButton"><i class="fa fa-times" aria-hidden="true"></i></span>
				</div>
			</div>
			<div class="fBlock sorting open" onclick="el.table.filter.openSubPopup(this,event)">
				<div class="ttl"><i class="fa fa-exchange" aria-hidden="true"></i> Сортировка</div>
				<div class="filterPopup _subPoup open">
					<div class="filterLines">
						<div class="filterLine">
							<span class="fName">Сортировать по </span>
							<span class="fSelect">
								<select name="" id="">
									<option value="">id</option>
									<option value="">name</option>
									<option value="">text</option>
								</select>
							</span>
							<span class="fHow">
								<input type="radio" name="sortHow"/>
								<input type="radio" name="sortHow"/>
							</span>
							<span class="fDel"><i class="fa fa-times" aria-hidden="true"></i></span>
						</div>
						<div class="filterLine">
							<span class="fName">Сортировать по </span>
							<span class="fSelect">
								<select name="" id="">
									<option value="">id</option>
									<option value="">name</option>
									<option value="">text</option>
								</select>
							</span>
							<span class="fHow">
								<input type="radio" name="sortHow"/>
								<input type="radio" name="sortHow"/>
							</span>
							<span class="fDel"><i class="fa fa-times" aria-hidden="true"></i></span>
						</div>
						<div class="filterLine">
							<span class="fName">Сортировать по </span>
							<span class="fSelect">
								<select name="" id="">
									<option value="">id</option>
									<option value="">name</option>
									<option value="">text</option>
								</select>
							</span>
							<span class="fHow">
								<input type="radio" name="sortHow"/>
								<input type="radio" name="sortHow"/>
							</span>
							<span class="fDel"><i class="fa fa-times" aria-hidden="true"></i></span>
						</div>
						<div class="filterButtons">
							<button class="clearbtn">очистить</button>
							<button class="apply">применить</button>
						</div>
					</div>
					<span onclick="el.table.filter.closeSubPopup(event)" class="closeButton"><i class="fa fa-times" aria-hidden="true"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="rightBlock">
		{# todo тут будет поиск по текущей выдаче #}
	</div>
</div>