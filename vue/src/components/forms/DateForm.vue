<template>
	<div class="date-form">
		<div class="date-form__top">
			<div class="date-form-time">
				<input
					v-model="inputDate"
					class="date-form-time__full-date"
					@keyup="changeInputDate"
				/>
				<div v-if="includeTime" class="date-form-time__time">
					<input
						v-model="inputTime"
						class="date-form-time__time-input"
						type="text"
						@keyup="changeInputTime"
					/>
				</div>
			</div>
		</div>
		<Datepicker
			placeholder="$('empty')"
			v-model="localDate"
			:inline="true"
			:language="currentLang"
			@selected="selectDate"
			:monday-first="this.$store.getters.lang === 'ru'"
		/>
		<div class="date-form__bottom">
			<div class="date-form__clear" @click="clear()">{{$t('clear')}}</div>
		</div>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	import * as locales from 'vuejs-datepicker/dist/locale';
	export default
	{
		props:
		{
			value:{default: null},
			includeTime:{type: Boolean, default: false },
		},
		components:{Datepicker},
		data()
		{
			return {
				localDate:'',
				inputDate:'',
				inputTime:'',
				currentLang: locales.en,
			}
		},
		mounted()
		{
			if(this.value)
				this.localDate = this.value;
			else
				this.localDate = new Date();
			this.inputTime = this.formatedTime();
			this.setLang();
		},
		watch:
		{
			localDate()
			{
				this.inputDate = this.formatedDate();
			}
		},
		methods:
		{
			clear()
			{
				this.localDate = '';
				this.$emit('selected', '');
			},
			setLang()
			{
				if (locales[this.$store.getters.lang])
					this.currentLang = locales[this.$store.getters.lang];
			},

			/**
			 * Selects date, and covert it to need format
			 */
			selectDate(date)
			{
				let day     = ('0'+date.getDate()).slice(-2),
					month   = ('0'+(date.getMonth()+1)).slice(-2),
					year    = date.getFullYear(),
					huors   = ('0'+date.getHours()).slice(-2),
					minutes = ('0'+date.getMinutes()).slice(-2);
				let newDate = `${year}-${month}-${day}`;

				if(this.includeTime)
					newDate = `${newDate} ${huors}:${minutes}`;
				this.$emit('selected', newDate);
			},

			/**
			 * Formats date string and change date
			 */
			changeInputDate(event)
			{
				let newDateStr = event.target.value;
				let dateArray = newDateStr.split('.');
				if(dateArray.length != 3 || dateArray[2].length <= 3)
					return false;

				let sortedArray = [dateArray[1], dateArray[0], dateArray[2]];
				let newDate = new Date(sortedArray.join('.'));
				if(!isNaN(newDate.getTime()))
					this.localDate = newDate;
				this.selectDate(newDate);
			},

			/**
			 * Formats time string and change time
			 */
			changeInputTime(event)
			{
				let newTimeString = event.target.value;
				let timeArray = newTimeString.split(':');
				if(timeArray.length != 2 || timeArray[0].length < 2 || timeArray[1].length < 2)
					return false;
				let huors = ('0'+timeArray[0]).slice(-2);
				if(huors > 23) huors = 23;
				let minutes = ('0'+timeArray[1]).slice(-2);
				if(minutes > 59) minutes = 59;

				this.inputTime = `${huors}:${minutes}`;
				this.localDate.setHours(huors);
				this.localDate.setMinutes(minutes);
				this.selectDate(this.localDate);
			},

			/**
			 * Return formated date string
			 */
			formatedDate()
			{
				if(!this.localDate)
					return this.$t('empty');
				let day    	   = ('0'+this.localDate.getDate()).slice(-2),
					month      = ('0'+(this.localDate.getMonth()+1)).slice(-2),
					year       = this.localDate.getFullYear();

				return `${day}.${month}.${year}`;
			},

			/**
			 * Returns formated time 00:00
			 */
			formatedTime()
			{
				if(!this.localDate)
					return '00:00';
				let huors   = ('0'+this.localDate.getHours()).slice(-2),
					minutes = ('0'+this.localDate.getMinutes()).slice(-2);

				return `${huors}:${minutes}`;
			}
		}
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
		padding: 10px 0 4px 0;
		width: 300px;
		.vdp-datepicker{position: static;}
		.vdp-datepicker__calendar
		{
			color: #191C21;
			left:-15px;
			top:44px;
			margin: 0 auto 10px;
			width: calc(100% - 20px);
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
				width: 60%;
				height: 30px;
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
		.vdp-datepicker__calendar .cell.today{color:#D01246;}
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
		padding-left: 9px;
		margin-bottom: 8px;
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
		max-width: 280px;
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
		padding-top: 4px;
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
		padding:10px;
		padding-left:14px;
		&:hover{background: rgba(103, 115, 135, 0.05);}
	}
</style>