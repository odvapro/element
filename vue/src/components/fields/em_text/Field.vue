<template>
	<div class="em-text" :class="{'em-text__table':(view != 'detail')}">
		<template v-if="view=='detail'">
			<div class="em-text__detail" @click="openEditor()">
				{{ localValue }}
			</div>
		</template>
		<template v-else>
			<input
				type="text"
				class="el-inp-noborder"
				@change="changeValue"
				:value="localValue"
				:placeholder="$t('empty')"
			/>
		</template>
	</div>
</template>
<script>
	export default
	{
		props: ['fieldValue','fieldSettings','mode', 'view'],
		components:{},
		data()
		{
			return {
				localValue:this.fieldValue,
				editorSettings:
				{
					theme: 'bubble'
				},
				toolbarOptions:
				[
					[
						'bold', 'italic','link', 'image',
						{ 'list': 'ordered'}, { 'list': 'bullet' },
						{ 'header': [1, 2, 3, 4, 5, 6, false] }
					]
				]
			}
		},
		methods:
		{
			/**
			 * Send change current value
			 */
			changeValue(event)
			{
				this.$emit('onChange', {
					value     :  event.target.value,
					settings  : this.fieldSettings
				});
			},
			openEditor()
			{
				alert('ok');
			}
		}
	}
</script>
<style lang="scss">
	.em-text
	{
		min-width:500px;
		&.em-text__table
		{
			min-width:auto;
			position: absolute;
			width: 100%;
			left: 0px;
			top: 0px;
			height: 100%;
			padding-left: 10px;
			padding-right: 10px;
			margin-top: 0;
		}
	}
	.em-text__detail
	{
		line-height: 17px;
		font-size: 12px;
		color: #677387;
		padding:11px 20px 11px 0;
		cursor: pointer;
	}
</style>