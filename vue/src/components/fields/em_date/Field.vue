<template>
	<div class="em-date">
		<template v-if="mode == 'edit'">
			<Datepicker
				v-model="localFieldValue"
				@selected="changeValue"
				placeholder="Empty"
			>
				<div slot="beforeCalendarHeader" class="em-date__clear">
					<button @click="clear()">Clear</button>
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
			}
		},
		mounted()
		{
			this.localFieldValue = this.fieldValue;
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
				var fullDate =  `${year}-${month}-${day}`;
				this.$emit('onChange', {
					value     : fullDate,
					settings  : this.fieldSettings
				});
			},
			/**
			 * Clear value
			 */
			clear()
			{
				this.localFieldValue = '';
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
			padding-bottom: 50px;
			left:-15px;
			top:44px;
			margin:0 auto;
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
	.em-date__clear
	{
		position: absolute;
		bottom:0px;
		width:100%;
		left:0px;
		height: 40px;
		border-top:1px solid rgba(103, 115, 135, 0.1);
		text-align:center;
		padding-top: 3px;
		button
		{
			background: none;
			border:none;
			font-size: 12px;
			cursor: pointer;
			&:hover{background: #F0F1F3;}
			display: block;
			width:100%;
			text-align: center;
			padding:10px 0;
		}
	}
</style>