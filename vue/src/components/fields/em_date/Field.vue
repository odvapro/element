<template>
	<div class="em-date-wr">
		<div class="em-date-wr__static-field" @click="openFieldEdit">
			<div
				class="em-date-wr__static-field-value"
				:class="{'em-date-wr__static-field-value_empty': isEmpty}"
			>{{ formatedDateTime }}</div>
		</div>
		<DateForm
			:value="localDate"
			:includeTime="includeTime"
			@selected="selectDate"
			v-click-outside="closeFieldEdit"
			v-if="isEditFieldPopup"
		/>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue', 'fieldSettings', 'mode', 'view'],
		data()
		{
			return {
				isEditFieldPopup: false,
				localDate:null,
			}
		},
		computed:
		{
			isEmpty()
			{
				return !this.localDate;
			},
			includeTime()
			{
				return this.fieldSettings.includeTime == 'true';
			},
			formatedDateTime()
			{
				if(!this.localDate)
					return this.$t('empty');
				let day    	   = ('0'+this.localDate.getDate()).slice(-2),
					monthIndex = this.localDate.getMonth(),
					year       = this.localDate.getFullYear(),
					huors      = ('0'+this.localDate.getHours()).slice(-2),
					minutes    = ('0'+this.localDate.getMinutes()).slice(-2);

				let monthes = this.$t('months');
				let monthName = monthes[monthIndex].substr(0,3);
				let dateStr = `${day} ${monthName} ${year}`;

				if(this.includeTime)
					dateStr = `${dateStr} ${huors}:${minutes}`;

				return dateStr;
			}
		},
		watch:
		{
			fieldValue(value)
			{
				this.localDate = this.convertToJsDate(value);
			},
		},
		mounted()
		{
			this.localDate = this.convertToJsDate(this.fieldValue);
		},
		methods:
		{
			openFieldEdit()
			{
				this.isEditFieldPopup = true;
			},
			closeFieldEdit()
			{
				this.isEditFieldPopup = false;
			},
			selectDate(newDate)
			{
				if(!newDate)
					this.closeFieldEdit();
				this.localDate = this.convertToJsDate(newDate);
				this.$emit('onChange', {
					value     : newDate,
					settings  : this.fieldSettings,
				});
			},
			convertToJsDate(sqlDate)
			{
				if(!sqlDate) return null;

				let newDate = new Date(Date.parse(sqlDate.replace(/-/g, '/')));

				if (isNaN(newDate.getTime())) return null;

				return newDate;
			},
		},
	}
</script>
<style lang="scss">
	.em-date-wr
	{
		padding-right: 10px;
		padding-left: 10px;
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		cursor: pointer;
	}
	.detail-field-box .em-date-wr
	{
		padding: 0;
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
</style>
