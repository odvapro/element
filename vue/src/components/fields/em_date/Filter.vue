<template>
	<div>
		<div
			class="em-date-filter-wr"
			v-if="showInput"
		>
			<div class="em-date-filter-wr__static-field" @click="openFieldEdit">
				<div
					class="em-date-filter-wr__static-field-value"
					:class="{'em-date-filter-wr__static-field-value_empty': !localFieldValue}"
				>{{ formatedLocalFullDateStr }} <span v-if="includeTime && localFullDate">{{ localTimeStr }}</span></div>
			</div>
			<div
				class="em-date-filter"
				v-click-outside="closeFieldEdit"
				v-if="isEditFieldPopup"
			>
				<div class="em-date-filter__top">
					<div class="em-date-filter-time">
						<div class="em-date-filter-time__full-date">
							{{ formatedLocalFullDateStr }}
						</div>
						<div v-if="includeTime && localFullDate" class="em-date-filter-time__time">
							<input
								class="em-date-filter-time__time-input"
								type="text"
								v-model="localTimeStr"
								@change="changeLocalTimeStr"
							>
						</div>
					</div>
				</div>
				<Datepicker
					v-model="localFullDate"
					:placeholder="$t('select_an_option')"
					@selected="changeLocalFieldValue"
					:inline="true"
					:language="currentLang"
					:monday-first="this.$store.getters.lang === 'ru'"
				>
				</Datepicker>
				<div class="em-date-filter__bottom">
					<div class="em-date-filter__clear" @click="clear()">{{$t('clear')}}</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	import * as locales from 'vuejs-datepicker/dist/locale';
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
				currentLang: locales.en
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
					return this.$t('select_an_option');

				let dateFieldValue = new Date(this.localFullDate),
					day = dateFieldValue.getDate() >= 10 ? dateFieldValue.getDate() : '0' + dateFieldValue.getDate(),
					month = this.getMonth(dateFieldValue.getMonth()),
					year = dateFieldValue.getFullYear();

					return `${day} ${month} ${year}`;
			},
		},
		methods:
		{
			checkAndSetPickerLang()
			{
				if (locales[this.$store.getters.lang])
					this.currentLang = locales[this.$store.getters.lang];
				else
					this.currentLang = locales.en;
			},
			getMonth(monthIndex)
			{
				let months = this.$t('months');
				if (!monthIndex || monthIndex > 11)
					return months[0].substr(0,3);

				return months[monthIndex].substr(0,3);
			},
			closeFieldEdit()
			{
				this.isEditFieldPopup = false;
			},
			changeValue()
			{

				let fieldDate = this.localFieldValue
				? this.localFieldValue
				: '';

				this.$emit('onChange', fieldDate);

				this.initFullDate(this.localFieldValue);
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

				this.changeLocalFieldValue(this.localFieldValue);
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

				this.changeValue();
			},
			formatToDoubleDigit(dig)
			{
				let number = Number(dig);
				if (number < 10)
					return '0' + number;
				return number;
			},
			clear()
			{
				this.localFieldValue = '';
				this.changeValue();
			},
		},
		mounted()
		{
			this.checkAndSetPickerLang();

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
	}
	.em-date-filter
	{
	    position: absolute;
		top: calc(100% + 1px);
		right: -1px;
		background-color: #fff;
		z-index: 10;

		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
		padding: 20px 0;
		width: 360px;
		.vdp-datepicker{position: static;}
		.vdp-datepicker__calendar
		{
			color: #191C21;
			left:-15px;
			top:44px;
			margin: 0 auto 30px;
			width: calc(100% - 40px);
			border: unset;
			background-color: transparent;
			.day__month_btn,
			.month__year_btn
			{
				border-radius: 2px;
			}
			header
			{
				font-size: 13px;
				line-height: 1.7;
				padding-top: 4px;
				width: 50%;
				height: 40px;
				.next,
				.prev
				{
					width: 22px;
					height: 22px;
					border-radius: 2px;
					&:hover
					{
						&:after
						{
							border-left-color: #677387;
							border-top-color: #677387;
						}
					}
					&:after
					{
						top: 30%;
						transform: translateX(-50%) translateY(-50%);
						width: 7px;
						height: 7px;
						border: 1px solid transparent;
						border-left-color: rgba(103, 115, 135, 0.4);
						border-top-color: rgba(103, 115, 135, 0.4);
					}
				}
				.next:after{transform: rotate(135deg) skew(-3deg, -3deg); margin-left: -6px;}
				.prev:after{transform: rotate(-45deg) skew(-3deg, -3deg); margin-left: -2px;}
			}
		}
		.vdp-datepicker__calendar .cell{font-size:13px;}
		.vdp-datepicker__calendar .cell.selected {background: rgba(124, 119, 145, 0.7); border-radius: 2px; color: #fff; }
		.vdp-datepicker__calendar .cell.selected:hover{background: rgba(124, 119, 145, 0.7); }
		.vdp-datepicker__calendar .cell{height: 38px; line-height: 38px; }
		.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).day:hover, .vdp-datepicker__calendar .cell:not(.blank):not(.disabled).month:hover, .vdp-datepicker__calendar .cell:not(.blank):not(.disabled).year:hover
		{
			border: 1px solid rgba(124, 119, 145, 0.1);
			border-radius: 2px;
			cursor:pointer;
		}
	}
	.em-date-filter-wr__static-field
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		overflow: hidden;
	}
	.em-date-filter-wr__static-field-value
	{
		font-size: 12px;
		color: #677387;
		white-space: nowrap;
		&_empty
		{
			color: rgba(103, 115, 135, 0.4);
		}
	}
	.em-date-filter__top
	{
		width: 100%;
		padding-left: 19px;
		margin-bottom: 20px;
	}
	.em-date-filter__time
	{
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
		justify-content: center;
	}
	.em-date-filter .em-date-filter__time-input
	{
		border: 1px solid #ccc;
		height: 40px;
		padding: 5px 10px;
		line-height: 50px;
		width: 50px;

	}
	.em-date-filter-time
	{
		max-width: 320px;
		width: 100%;
		display: flex;
		background-color: rgba(240, 241, 243, 0.5);
		border: 1px solid rgba(103, 115, 135, 0.1);
		border-radius: 2px;
		font-family: $rMedium;
		color: rgba(25, 28, 33, 0.7);
		font-size: 11px;
		line-height: 13px;
		padding: 8px 10px;
		height: 33px;
	}
	.em-date-filter-time__full-date
	{
		margin-right: 15px;
		padding-top: 1px;
	}
	.em-date-filter__bottom
	{
		border-top: 1px solid rgba(103, 115, 135, 0.1);

		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
		padding-top: 15px;
	}
	.em-date-filter-time__time
	{
		height: 15px;
		width: 100px;
		padding-left: 15px;
		border-left: 1px solid rgba(103, 115, 135, 0.1);
		.em-date-filter-time__time-input
		{
			padding-top: 0;
			padding-bottom: 0;
			height: 100%;
			width: 100%;
			line-height: 13px;
			font-size: 11px;
			color: rgba(25, 28, 33, 0.7);
			font-family: $rMedium;
			border: unset;
			background-color: transparent;
			&::placeholder {color: rgba(103, 115, 135, 0.4); }
		}
	}
	.em-date-filter__clear
	{
		font-size: 12px;
		cursor: pointer;
	}
</style>