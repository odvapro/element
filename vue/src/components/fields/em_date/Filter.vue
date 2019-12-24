<template>
	<div>
		<!-- <input
			type="text"
			v-if="showInput"
			class="filters-popup__filter-input el-inp"
			placeholder="Value"
			@keyup="changeValue"
			:value="filter.value"
		/> -->
		<div
			class="em-date-filter-wr"
			v-if="showInput"
		>
			<div class="em-date-wr">
				<div class="em-date-wr__static-field" @click="openFieldEdit">
					<div
						class="em-date-wr__static-field-value"
						:class="{'em-date-wr__static-field-value_empty': !localFieldValue}"
					>{{ formatedLocalFullDateStr }} <span v-if="includeTime && localFullDate">{{ localTimeStr }}</span></div>
				</div>
				<div
					class="em-date"
					v-click-outside="closeFieldEdit"
					v-if="isEditFieldPopup"
				>
					<div class="em-date__top">
						<div class="em-date-time">
							<div class="em-date-time__full-date">
								{{ formatedLocalFullDateStr }}
							</div>
							<div v-if="includeTime && localFullDate" class="em-date-time__time">
								<input
									class="em-date-time__time-input"
									type="text"
									v-model="localTimeStr"
									@change="changeLocalTimeStr"
								>
							</div>
						</div>
					</div>
					<Datepicker
						v-model="localFullDate"
						placeholder="Empty"
						@selected="changeLocalFieldValue"
						:inline="true"
					>
					</Datepicker>
					<div class="em-date__bottom">
						<div class="em-date__clear" @click="clear()">Clear</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	export default
	{
		props: ['filter', 'settings'],
		components:{Datepicker},
		data()
		{
			return {
				isEditFieldPopup: false,
				includeTime: false,
				localFullDate: false,
				localFieldValue: false,
				localTimeStr: false,
				localHours: false,
				localMinutes: false,
			}
		},
		computed:
		{
			showInput()
			{
				let emptyCollations = ['IS EMPTY','IS NOT EMPTY'];
				if(emptyCollations.indexOf(this.filter.operation) != -1)
					return false;
				return true;
			},
			formatedLocalTimeStr()
			{
				let hours = Number(this.localHours) >= 10 ? Number(this.localHours) : '0' + Number(this.localHours),
					minutes = Number(this.localMinutes) >= 10 ? Number(this.localMinutes) : '0' + Number(this.localMinutes);

				return `${hours}:${minutes}`
			},
			formatedLocalFullDateStr()
			{
				if (!this.localFullDate)
					return 'Empty';

				let dateFieldValue = new Date(this.localFullDate),
					day = dateFieldValue.getDate() >= 10 ? dateFieldValue.getDate() : '0' + dateFieldValue.getDate(),
					month = this.getMonth(dateFieldValue.getMonth()),
					year = dateFieldValue.getFullYear();

					return `${day} ${month} ${year}`;
			},
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				this.$emit('onChange', event.target.value);
			},
			getMonth(monthIndex)
			{
				let months =
				[
					'January',
					'February',
					'March',
					'April',
					'May',
					'June',
					'July',
					'August',
					'September',
					'October',
					'November',
					'December'
				];
				if (!monthIndex || monthIndex > 11)
					return months[0].substr(0,3);

				return months[monthIndex].substr(0,3);
			},
			closeFieldEdit()
			{
				this.isEditFieldPopup = false;

				let fieldDate = this.localFullDate
				? this.localFieldValue
				: '';

				this.$emit('onChange', fieldDate);
			},
			openFieldEdit()
			{
				this.isEditFieldPopup = true;
			},
			initFullDate(date = false)
			{
				let dateToInit = date === false
				? this.filter.value
				: date;

				if (dateToInit)
				{
					this.localFullDate = new Date(dateToInit);
					this.localFieldValue = dateToInit;
				}
				else
				{
					this.localFullDate = '';
					this.localFieldValue = false;
				}
					this.initTime(this.localFullDate);
			},
			initTime(date)
			{
				if (this.includeTime)
				{
					if (date === '')
					{
						this.localHours = 0;
						this.localMinutes = 0;
					}
					else
					{
						this.localHours = date.getHours();
						this.localMinutes = date.getMinutes();
					}
					this.localTimeStr = this.formatedLocalTimeStr;
				}
				else
				{
					this.localHours = this.localMinutes = this.localTimeStr = false;
				}
			},
			changeLocalTimeStr()
			{
				let tempTime = this.localTimeStr.replace(/\D/g,'').substr(0,4) || '0000';

				this.localHours = tempTime.substr(0,2);
				this.localMinutes = tempTime.substr(2,2);

				if(this.localHours > 23 || this.localHours < 0 || typeof +this.localHours !== 'number')
					this.localHours = 0;

				if(this.localMinutes > 59 || this.localMinutes < 0 || typeof +this.localMinutes !== 'number')
					this.localMinutes = 0;

				this.localTimeStr = this.formatedLocalTimeStr;
			},
			changeLocalFieldValue(newDate)
			{
				let currentData = new Date(newDate);

				let day           = this.formatToDoubleDigit(currentData.getDate()),
					month         = this.formatToDoubleDigit(currentData.getMonth() + 1),
					year          = currentData.getFullYear(),
					hours         = this.formatToDoubleDigit(this.localHours),
					minutes       = this.formatToDoubleDigit(this.localMinutes);

				if (this.includeTime)
					this.localFieldValue = `${year}-${month}-${day} ${hours}:${minutes}`;
				else
					this.localFieldValue = `${year}-${month}-${day}`;

				this.initFullDate(this.localFieldValue);
			},
			formatToDoubleDigit(dig)
			{
				if (dig < 10)
					return '0' + dig;
				return dig;
			},
			clear()
			{
				this.initFullDate('');
			},
		},
		mounted()
		{
			this.includeTime = this.settings.includeTime == "true";
			this.initFullDate();
		}
	}
</script>
<style lang="scss">
	.em-date-filter-wr
	{
		position: relative;
		display: flex;
		align-items: center;
		border: 1px solid rgba(103,115,135,0.4);
		height: 30px;
		padding-left: 4px;
		padding-right: 10px;
		border-radius: 2px;
		margin-right: 10px;
		min-width: 150px;
		&:hover{border: 1px solid rgba(103,115,135,0.7);}
		.em-date
		{
			left: unset;
			right: -1px;
			top: calc(100% + 1px);
		}
	}
</style>