<template>
	<div class="em-date">
		<template v-if="mode == 'edit'">
			<Datepicker
				v-model="localFieldValue"
				@selected="changeValue"
				placeholder="Empty"
			>
				<div slot="beforeCalendarHeader" class="">
					<div class="em-date__time">
						<!-- <input type="number" class="em-date__time-input" v-model="localHours" @change="changeTime" min="0" max="24">
						<input type="number" class="em-date__time-input" v-model="localMinutes" @change="changeTime" min="0" max="59"> -->
					</div>
					<div class="em-date__top">
						<div class="em-date-time">
							<div class="em-date-time__full-date">
								14 May 2020
							</div>
							<div class="em-date-time__time-date">
								18:30
							</div>
						</div>
					</div>
					<div class="em-date__bottom">
						<button class="em-date__clear" @click="clear()">Clear</button>
					</div>
				</div>
			</Datepicker>
		</template>
		<template v-else>
			{{ fieldValue }}
			<span v-if="!fieldValue" class="el-empty">Empty</span>
		</template>
	</div>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components:{Datepicker},
		data()
		{
			return {
				localFieldValue:'',
				localHours: 0,
				localMinutes: 0
			}
		},
		mounted()
		{
			if(!!this.fieldValue)
			{
				this.localFieldValue = new Date(this.fieldValue);
				this.localHours = this.localFieldValue.getHours();
				this.localMinutes = this.localFieldValue.getMinutes();
			}
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(newDate)
			{
				newDate = new Date(newDate);
				let day = newDate.getDate().toString(),
				month   = newDate.getMonth().toString(),
				year    = newDate.getFullYear().toString();

				month = (+month + 1).toString();
				month = (month.length == 1) ? '0' + month : month;
				day   = (day.length == 1) ? '0' + day : day;

				let hours = (+this.localHours < 10 ? ('0' + this.localHours) : this.localHours),
					minutes = (+this.localMinutes < 10 ? ('0' + this.localMinutes) : this.localMinutes),
					fullDate =  `${year}-${month}-${day} ${hours}:${minutes}`;

				this.$emit('onChange', {
					value     : fullDate,
					settings  : this.fieldSettings
				});
			},

			changeTime()
			{
				this.localFieldValue.setHours(this.localHours);
				this.localFieldValue.setMinutes(this.localMinutes);
			},

			/**
			 * Clear value
			 */
			clear()
			{
				this.localFieldValue = '';
				this.localHours = 0;
				this.localMinutes = 0;
				this.$el.querySelector('input').blur()
				this.$emit('onChange', {
					value     : '',
					settings  : this.fieldSettings
				});
			}
		}
	}
</script>
<style lang="scss">
	.em-date
	{
	    width:100%;
	    position: absolute;
	    top:0px;
	    left:0px;
	    padding-right: 10px;
	    padding-left: 10px;
		input
		{
			border: 0px;
			width: 100%;
			height: 100%;
			background: none;
			line-height: 49px;
			font-size: 12px;
			color: #677387;
			height: 49px;
			vertical-align: top;
			&::placeholder {color: rgba(103, 115, 135, 0.4); }
		}
		// .vdp-datepicker{width:100%;}
		.vdp-datepicker__calendar
		{
			border: 1px solid rgba(103, 115, 135, 0.1);
			border-radius: 2px;
			box-shadow: 0px 4px 6px rgba(200, 200, 200, 0.25);
			padding: 21px;
			color: #191C21;
			padding-bottom: 100px;
			left:-15px;
			top:44px;
			margin:0 auto;

			width: 360px;
			padding-top: 83px;
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
	.em-date__top
	{
		position: absolute;
		width: 100%;
		top: 0;
		left: 0;
		padding-left: 19px;
		padding-top: 20px;
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
		padding: 14px 10px;
	}
	.em-date-time__full-date
	{
		border-right: 1px solid rgba(103, 115, 135, 0.1);
		padding-right: 15px;
		margin-right: 15px;
	}
	.em-date__bottom
	{
		border-top: 1px solid rgba(103, 115, 135, 0.1);
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
	}
	.em-date__clear
	{
		bottom:0px;
		width:100%;
		height: 73px;
		border-top:1px solid rgba(103, 115, 135, 0.1);
		text-align:center;
		padding-top: 3px;

		/*button
		{*/
			background: none;
			border:none;
			font-size: 12px;
			cursor: pointer;
			display: block;
			text-align: center;
			padding: 10px 0;
			&:hover{background: #F0F1F3;}
		/*}*/
	}
</style>