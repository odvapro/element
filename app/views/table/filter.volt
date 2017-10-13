<div id="filterLine">
	<div class="filterLine__wrap">
		<form
			data-tviewid="{{ (currentTableView)?currentTableView.id:'' }}"
			onsubmit="el.table.view.saveTviewSettings(this); return false;"
		>
			<input type="hidden" name="viewid" value="{{ (currentTableView)?currentTableView.id:'' }}">
			<div class="filterBlock">
				<div class="filterBlock__title">
					<i class="fa fa-table" aria-hidden="true"></i>
					{{ (currentTableView)?currentTableView.name:'Основное отображение' }}
				</div>
				<div class="filterBlock__popup">
					<ul>
						<li><a href="/table/{{ tableInfo['table'] }}/">Основное отображение</a></li>
						{% for tableView in tableViews %}
							<li><a href="{{ tableView.getUrl() }}">{{ tableView.name }}</a></li>
						{% endfor %}
					</ul>
					<div class="filterBlock__viewAddLine" onclick="el.table.view.showAddPopup()">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
						Добваить
					</div>
				</div>
			</div>
			<div class="filterBlock">
				<div class="filterBlock__title">
					<i class="fa fa-eye-slash" aria-hidden="true"></i>
					Скрытые поля
				</div>
				<div class="filterBlock__popup">
					<div class="filterInput">
						<input
							class="filterBlock__sqlInput"
							type="text"
							name="columns"
							value="{{ (currentTableView)?currentTableView.columns:'' }}"
							onkeyup="el.table.view.showApplyInput(this)"
						/>
						<button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
			<div class="filterBlock">
				<div class="filterBlock__title">
					<i class="fa fa-filter" aria-hidden="true"></i>
					Фильтр
				</div>
				<div class="filterBlock__popup">
					<div class="filterInput">
						<input
							class="filterBlock__sqlInput"
							type="text"
							name="filter"
							value="{{ (currentTableView)?currentTableView.filter:'' }}"
							onkeyup="el.table.view.showApplyInput(this)"
						/>
						<button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
			<div class="filterBlock">
				<div class="filterBlock__title">
					<i class="fa fa-sort" aria-hidden="true"></i>
					Сортировка
				</div>
				<div class="filterBlock__popup">
					<div class="filterInput">
						<input
							class="filterBlock__sqlInput"
							type="text"
							name="sort"
							value="{{ (currentTableView)?currentTableView.sort:'' }}"
							onkeyup="el.table.view.showApplyInput(this)"
						/>
						<button type="submit"><i class="fa fa-check" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
			<div class="filterBlock">
				<div class="filterBlock__title">
					<i class="fa fa-ellipsis-h" aria-hidden="true"></i>
				</div>
				<div class="filterBlock__popup filterBlock__popup--dark">
					<ul>
						<li onclick="el.table.view.showDeletePopup({{ (currentTableView)?currentTableView.id:'' }})">
							<i class="fa fa-trash" aria-hidden="true"></i>
							Удалить отображение
						</li>
						<li>
							<i class="fa fa-pencil" aria-hidden="true"></i>
							Переименовать отображение
						</li>
						<li>
							<i class="fa fa-download" aria-hidden="true"></i>
							Скачать CSV
						</li>
						<li>
							<i class="fa fa-clone" aria-hidden="true"></i>
							Дублировать отображение
						</li>
					</ul>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="filyetTPLS" style="display:none;">
	<div class="_addTviewPopup">
		<div class="popupCont"><div class="popupTopLine">
			<span class="name">Добавление отображения поля</span>
			<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
		</div>
		<form id="fieldSettings" onsubmit="el.table.view.add(this); return false;" method="post">
			<div class="popupContLine">
				<div class="editLine">
					<div class="name">Введите название отображения</div>
					<div class="inp">
						<input
							name="tViewName"
							type="text"
							value=""
							placeholder="Введите название отображения"
						/>
					</div>
				</div>
			</div>
			<div class="popupBottomLine">
				<input type="hidden" name="tableName" value="{{ tableInfo['table'] }}">
				<button class="elbutton blue" type="submit">Сохранить</button>
				<button class="elbutton gray" onclick="el.popup.hide(); return false;">Отмена</button>
			</div>
		</form>
		</div>
	</div>
	<div class="_deleteTviewPopup">
		<div class="popupCont"><div class="popupTopLine">
			<span class="name">Удаление отображения</span>
			<span class="icon closeBtn10" onclick="el.popup.hide();"></span>
		</div>
		<form id="fieldSettings" onsubmit="el.table.view.delete(this); return false;" method="post">
			<div class="popupBottomLine">
				<input type="hidden" name="viewId" value="{{ (currentTableView)?currentTableView.id:'' }}">
				<button class="elbutton blue" type="submit">Удалить</button>
				<button class="elbutton gray" onclick="el.popup.hide(); return false;">Отмена</button>
			</div>
		</form>
		</div>
	</div>
</div>