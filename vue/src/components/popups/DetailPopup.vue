<template>
	<div class="detail-popup">
		<Popup
			:visible.sync="isPopupVisible"
		>
			<Detail
				:tableCode="tableCode"
				:name="name"
				:id="id"
				:element="element"
				@cancel="cancel"
				@openDetail="openDetail"
				@saveElement="saveElement"
				@removeElement="removeElement"
				@createElement="createElement"
			/>
		</Popup>
	</div>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue'
	export default
	{
		props: ['tableCode', 'name', 'id', 'visible', 'element'],
		components: {Detail},
		computed:
		{
			isPopupVisible:
			{
				get()
				{
					return this.visible
				},
				set(val)
				{
					this.$emit('update:visible', val);
				}
			}
		},
		methods:
		{
			cancel()
			{
				this.isPopupVisible = false;
			},
			openDetail(data)
			{
				this.$emit('openDetail', data);
			},
			saveElement(data)
			{
				this.$emit('saveElement', data);
			},
			removeElement(data)
			{
				this.$emit('removeElement', data);
			},
			createElement(data)
			{
				this.$emit('createElement', data);
			},
		}
	}
</script>
<style lang="scss">
	.detail-popup
	{
		position: absolute;
		z-index: 10;
		.popup-close{display: none;}
		.popup-block {min-width: 800px; }
		.detail
		{
			padding:0;
			height:calc(100% - 144px);
			overflow: unset;
			display: flex;
			flex-direction: column;
			.detail-feilds
			{
				margin-top: auto;
				margin-bottom: auto;
			}
		}
		.detail-head{padding-right: 0px; }
	}
</style>