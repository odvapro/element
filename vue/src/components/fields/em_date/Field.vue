<template>
	<div class="em-date-wr">
		<div class="em-date-wr__static-field" @click="openFieldEdit">
			<div
				class="em-date-wr__static-field-value"
				:class="{'em-date-wr__static-field-value_empty': !localFieldValue}"
			>{{ formatedDateValue }} <span v-if="isTimeAllow">{{ formatedLocalTime }}</span></div>
		</div>
		<div
			class="em-date"
			v-click-outside="closeFieldEdit"
			v-if="isEditFieldPopup"
		>
			<div class="em-date__top">
				<div class="em-date-time">
					<div class="em-date-time__full-date">
						{{ formatedDateValue }}
					</div>
					<div v-show="isTimeAllow" class="em-date-time__time">
						<input
							class="em-date-time__time-input"
							type="text"
							v-model="localTime"
							@change="changeTime"
							@click="focusInput"
						>
					</div>
				</div>
			</div>
			<Datepicker
				v-model="localFieldValue"
				placeholder="Empty"
				@selected="changeValue"
				:inline="true"
			>
			</Datepicker>
			<div class="em-date__bottom">
				<div class="em-date__time-allow" @click="toggleTimeAllow">
					<div class="em-date__time-allow-label">Include Time</div>
					<div
						class="em-date__time-allow-select"
						:class="{'em-date__time-allow-select_active': isTimeAllow}"
					></div>
				</div>
				<div class="em-date__clear" @click="clear()">Clear</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	export default
	{
		props: ['fieldValue', 'fieldSettings', 'mode', 'view', 'isEditFieldPopup'],
		components:{Datepicker},
		data()
		{
			return {
				localFieldValue:'',
				localHours: 0,
				localMinutes: 0,
				localTime: '',
				isTimeAllow: false,
				fieldIsEdit: false,
			}
		},
		mounted()
		{
			if(!!this.fieldValue)
			{
				this.localFieldValue = new Date(this.fieldValue);
				this.localHours = this.localFieldValue.getHours();
				this.localMinutes = this.localFieldValue.getMinutes();
				this.initTime();
			}
		},
		computed:
		{
			formatedDateValue()
			{
				if (!this.fieldValue)
					return 'Empty';

				let dateFieldValue = new Date(this.fieldValue),
					day = dateFieldValue.getDate() >= 10 ? dateFieldValue.getDate() : '0' + dateFieldValue.getDate(),
					month = this.getMonth(dateFieldValue.getMonth()),
					year = dateFieldValue.getFullYear();

					return `${day} ${month} ${year}`;
			},
			formatedLocalTime()
			{
				let hours = Number(this.localHours) >= 10 ? Number(this.localHours) : '0' + Number(this.localHours),
					minutes = Number(this.localMinutes) >= 10 ? Number(this.localMinutes) : '0' + Number(this.localMinutes);

				return `${hours}:${minutes}`
			}
		},
		methods:
		{
			openFieldEdit()
			{
				this.$emit('openEditFieldPopup');
			},
			closeFieldEdit()
			{
				this.$emit('closeEditFieldPopup');
			},
			initTime()
			{
				if (this.localFieldValue.getSeconds())
				{
					this.isTimeAllow = true;

					if (!this.localHours)
						this.localHours = 0;

					if (!this.localMinutes)
						this.localMinutes = 0;
				}
				else
				{
					this.localHours = 0;
					this.localMinutes = 0;
				}


				this.localTime = this.formatedLocalTime;

			},
			toggleTimeAllow()
			{

				if (this.localFieldValue)
				{
					this.isTimeAllow = !this.isTimeAllow;
					if (this.isTimeAllow && this.localFieldValue)
					{
						this.localTime = this.formatedLocalTime;
						this.localFieldValue.setSeconds(1);
					}
					else
					{
						this.localTime = '';
						this.localFieldValue.setSeconds(0);
						this.localMinutes = 0;
						this.localHours = 0;
					}
				}
			},
			focusInput()
			{
				event.target.focus();
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
			/**
			 * Send change current value
			 */
			changeValue(newDate)
			{
				newDate = new Date(newDate);

				let day 	= newDate.getDate().toString(),
					month   = newDate.getMonth().toString(),
					year    = newDate.getFullYear().toString(),
					hours   = this.localHours,
					minutes = this.localMinutes,
					fullDate;

				month = (+month + 1).toString();
				month = (month.length == 1) ? '0' + month : month;
				day   = (day.length == 1) ? '0' + day : day;

				if (this.isTimeAllow)
					fullDate =  `${year}-${month}-${day} ${hours}:${minutes}`;
				else
					fullDate =  `${year}-${month}-${day}`;

				this.$emit('onChange', {
					value     : fullDate,
					settings  : this.fieldSettings
				});
			},

			changeTime()
			{
				let tempTime = this.localTime.replace(/\D/g,'').substr(0,4) || '0000';

				this.localHours = tempTime.substr(0,2);
				this.localMinutes = tempTime.substr(2,2);

				if(this.localHours > 23 || this.localHours < 0 || typeof +this.localHours !== 'number')
					this.localHours = '00';

				if(this.localMinutes > 59 || this.localMinutes < 0 || typeof +this.localMinutes !== 'number')
					this.localMinutes = '00';

				this.localTime = this.formatedLocalTime;
			},

			/**
			 * Clear value
			 */
			clear()
			{
				this.localFieldValue = '';
				this.localHours = 0;
				this.localMinutes = 0;
				this.$emit('onChange', {
					value     : '',
					settings  : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-date-wr
	{
		padding-right: 10px;
		padding-left: 10px;
		width: 100%;
		height: 100%;
	}
	.em-date
	{
	    position: absolute;
		top: 100%;
		left: 0px;
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
	.em-date-wr__static-field
	{
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		overflow: hidden;
	}
	.em-date-wr__static-field-value
	{
		font-size: 12px;
		color: #677387;
		white-space: nowrap;
		&_empty
		{
			color: rgba(103, 115, 135, 0.4);
		}
	}
	.em-date__top
	{
		width: 100%;
		padding-left: 19px;
		margin-bottom: 20px;
	}
	.em-date__time
	{
		display: flex;
		flex-wrap: nowrap;
		flex-direction: row;
		justify-content: center;
	}
	.em-date .em-date__time-input
	{
		border: 1px solid #ccc;
		height: 40px;
		padding: 5px 10px;
		line-height: 50px;
		width: 50px;

	}
	.em-date-time
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
	.em-date-time__full-date
	{
		margin-right: 15px;
		padding-top: 1px;
	}
	.em-date__bottom
	{
		border-top: 1px solid rgba(103, 115, 135, 0.1);

		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
		padding-top: 15px;
	}
	.em-date-time__time
	{
		height: 15px;
		width: 100px;
		padding-left: 15px;
		border-left: 1px solid rgba(103, 115, 135, 0.1);
		.em-date-time__time-input
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
	.em-date__time-allow
	{
		margin-bottom: 16px;
		display: flex;
		justify-content: space-between;
	}
	.em-date__time-allow-label
	{
		font-size: 12px;
	}
	.em-date__time-allow-select
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
	.em-date__clear
	{
		font-size: 12px;
		cursor: pointer;
	}
</style>