<template>
	<div class="detail-popup">
		<Popup
			:visible.sync="addPopupVisible"
		>
			<Detail
				:tableCode="tableCode"
				:name="name"
				:id="id"
				@cancel="cancel"
				@openCreatedDetail="openCreatedDetail"
			/>
		</Popup>
	</div>
</template>
<script>
	import Detail from '@/components/tviews/Detail.vue'
	export default
	{
		props: ['tableCode', 'name', 'id', 'isPopupVisible'],
		components: {Detail},
		data()
		{
			return {
				addPopupVisible: false,
			}
		},
		watch:
		{
			'isPopupVisible'(newValue)
			{
				this.addPopupVisible = newValue;
			},
			'addPopupVisible'(newValue)
			{
				if (!newValue)
					this.$emit('close');
			}
		},
		methods:
		{
			cancel()
			{
				this.$emit('close');
			},
			openCreatedDetail({tableCode,id})
			{
				this.$emit('openCreatedDetail', {tableCode, id})
				// this.$router.push({name:'tableDetail', params:{tableCode:tableCode, id:id }});
			}
		}
	}
</script>
<style lang="scss">
	.detail-popup
	{
		position: absolute;
		z-index: 1;
		.popup-block
		{
			min-width: 1000px;
		}
	}
</style>