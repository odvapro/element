<template>
	<div class="em-date-wr">
		<div class="em-date-wr__static-field" @click="openFieldEdit">
			<div
				class="em-date-wr__static-field-value"
				:class="{'em-date-wr__static-field-value_empty': isEmpty}"
			>{{ localValue }}</div>
		</div>
		<DateForm
			:fieldValue="fieldValue"
			:fieldSettings="fieldSettings"
			@changeValue="changeValue"
			@changeLocalValue="changeLocalValue"
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
				localValue: this.$t('empty'),
			}
		},
		computed:
		{
			isEmpty()
			{
				if(this.localValue == this.$t('empty'))
					return true;
			}
		},
		mounted()
		{
			if (this.fieldValue)
				this.localValue = this.formatDate(this.fieldValue);
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
			changeValue(val)
			{
				this.$emit('onChange', val);
			},
			changeLocalValue(localVal)
			{
				this.localValue = localVal;
			},
			formatDate(date)
			{
				let day   = date.match(/-\d{2}/g)[1].replace(/-/,''),
					month = this.getMonth(date.match(/-\d{2}/g)[0].replace(/-/,'') - 1),
					year  = date.match(/\d{4}/)[0];

				let newDate = `${day} ${month} ${year}`;

				if (this.fieldSettings.includeTime)
					if(date.match(/:/))
					{
						let hours   = date.match(/\d{2}:/)[0].replace(/:/, ''),
							minutes = date.match(/:\d{2}/)[0].replace(/:/, '');

						newDate += ` ${hours}:${minutes}`;
					}
					else
					{
						newDate += ' 00:00';
					}

				return newDate;
			},
			getMonth(monthIndex)
			{
				let months = this.$t('months');
				if (!monthIndex || monthIndex > 11)
					return months[0].substr(0,3);

				return months[monthIndex].substr(0,3);
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