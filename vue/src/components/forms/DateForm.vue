<template>
	<div class="date-form">
		<div class="date-form__top">
			<div class="date-form-time">
				<div class="date-form-time__full-date" v-if="!includeTime">
					{{ formatedLocalFullDateStr }}
				</div>
				<input 
					class="date-form-time__full-date"
					v-model="inputDate"
					@keyup.enter="getInputDate"
					v-if="includeTime"
				>
				<div v-if="includeTime && localFullDate" class="date-form-time__time">
					<input
						class="date-form-time__time-input"
						type="text"
						v-model="localTimeStr"
						@change="changeLocalTimeStr"
					>
				</div>
			</div>
		</div>
		<Datepicker
			:value="localFullDate"
			placeholder="$('empty')"
			@selected="changeLocalFieldValue"
			:inline="true"
			:language="curentLang"
			:format="formatedLocalFullDateStr"
		/>
		<div class="date-form__bottom">
			<div class="date-form__clear" @click="clear()">{{$t('clear')}}</div>
		</div>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	import {en, ru} from 'vuejs-datepicker/dist/locale';

	export default
	{
		props: ['fieldValue', 'fieldSettings'],
		components:{Datepicker},
		data()
		{
			return {
				inputDate: '',
				localFullDate: false,
				localTimeStr: '',
				localHours: false,
				localMinutes: false,
				localFieldValue: false,
				includeTime: false,
				curentLang: en,
				datePickerLocales:
				{
					en: en,
					ru: ru
				}
			}
		},
		mounted()
		{
			this.checkAndSetPickerLang();

			this.includeTime = this.fieldSettings.includeTime == "true";

			this.initFullDate();
			this.changeLocalValue()
		},
		beforeDestroy()
		{
			this.changeValue();
		},
		methods:
		{
			getInputDate()
			{
				let dateArray = this.inputDate.split('.');
					if(dateArray.length>0)
					{
						let sortedArray = [dateArray[1], dateArray[0], dateArray[2]];
						let newData = sortedArray.join('.')
						this.localFullDate = newData;
					}
				this.inputDate = this.formatedLocalFullDateStr
			},
			changeLocalValue()
			{
				let newValue = this.formatedLocalFullDateStr;
				if (this.includeTime && newValue !== this.$t('empty'))
					newValue += ` ${this.formatedLocalTimeStr}`;

				this.$emit('changeLocalValue', newValue);
			},
			checkAndSetPickerLang()
			{
				for (let lang in this.datePickerLocales)
					if (lang === this.$store.state.languages.currentLang.short)
					{
						this.curentLang = this.datePickerLocales[lang];
						break;
					}
			},
			changeValue(newValue)
			{
				if (this.localFullDate)
					this.changeLocalFieldValue(this.localFullDate);

				let fieldDate;
				if (typeof newValue === 'undefined')
					fieldDate = this.localFullDate
					? this.localFieldValue
					: '';
				else
					fieldDate = newValue;

				this.$emit('changeValue', {
					value     : fieldDate,
					settings  : this.fieldSettings
				});
			},
			getMonth(monthIndex)
			{
				let months = this.$t('months');
				if (!monthIndex || monthIndex > 11)
					return months[0].substr(0,3);

				return months[monthIndex].substr(0,3);
			},
			initFullDate(date = false)
			{
				let dateToInit = date === false
				? this.fieldValue
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
					this.inputDate    = this.formatedLocalFullDateStr;
				}
				else
				{
					this.localHours = this.localMinutes = false;
					this.localTimeStr = '';
				}
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
					return '0' + +dig;
				return +dig;
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
			clear()
			{
				
				this.initFullDate(new Date());
			},
		},
		computed:
		{
			formatedLocalFullDateStr()
			{
				if (!this.localFullDate)
					this.localFullDate = new Date;

				let dateFieldValue = new Date(this.localFullDate),
					day = dateFieldValue.getDate() >= 10 ? dateFieldValue.getDate() : '0' + dateFieldValue.getDate(),
					month = this.getMonth(dateFieldValue.getMonth()),
					year = dateFieldValue.getFullYear();

					return `${day} ${month} ${year}`;
			},
			formatedLocalTimeStr()
			{
				let hours = Number(this.localHours) >= 10 ? Number(this.localHours) : '0' + Number(this.localHours),
					minutes = Number(this.localMinutes) >= 10 ? Number(this.localMinutes) : '0' + Number(this.localMinutes);

				return `${hours}:${minutes}`
			}
		},
	}
</script>

<style lang="scss">
	.date-form
	{
		position: absolute;
		top: 95%;
		left: -3px;
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
	.date-form__top
	{
		width: 100%;
		padding-left: 19px;
		margin-bottom: 20px;
	}
	.date-form__time
	{
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
		justify-content: center;
	}
	.date-form .date-form__time-input
	{
		border: 1px solid #ccc;
		height: 40px;
		padding: 5px 10px;
		line-height: 50px;
		width: 50px;

	}
	.date-form-time
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
	.date-form-time__full-date
	{
		margin-right: 15px;
		padding-top: 1px;
		border: none;
		background: none;
		max-width: 80px;
		color: rgba(25, 28, 33, 0.7);
	}
	.date-form__bottom
	{
		border-top: 1px solid rgba(103, 115, 135, 0.1);

		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
		padding-top: 15px;
	}
	.date-form-time__time
	{
		height: 15px;
		width: 100px;
		padding-left: 15px;
		border-left: 1px solid rgba(103, 115, 135, 0.1);
		.date-form-time__time-input
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
	.date-form__time-allow
	{
		margin-bottom: 16px;
		display: flex;
		justify-content: space-between;
	}
	.date-form__time-allow-label
	{
		font-size: 12px;
	}
	.date-form__time-allow-select
	{
		width: 38px;
		height: 20px;
		background-color: rgba(103, 115, 135, 0.4);
		border-radius: 20px;
		transition: all .25s;
		position: relative;
		cursor: pointer;
		&:after
		{
			content: '';
			position: absolute;
			background-color: #fff;
			border-radius: 50%;
			width: 16px;
			height: 16px;
			top: 2px;
			left: 2px;
			transition: all .25s;
		}
		&_active
		{
			background-color: rgb(46, 170, 220);
			&:after
			{
				left: calc(100% - 2px);
				transform: translateX(-100%);
			}
		}
	}
	.date-form__clear
	{
		font-size: 12px;
		cursor: pointer;
	}
</style>