<template>
<div class="settings-groups__wrapper">
	<div class="settings-groups__head">
		<button @click="addGroup" class="el-btn">{{$t('createGroup')}}</button>
	</div>
	<div class="settings-groups__content">
		<ul class="settings-groups__groups-content">
			<li class="settings-groups__group-wrapper" v-for="group, groupIndex in groups">
				<div class="settings-groups__group-head">
					<div class="settings-groups__group-info">
						<div class="settings-groups__group-name" :class="{disabled: group.name == 'Administrators'}"><input type="text" v-model="group.name"></div>
						<div class="settings-groups__member-info">{{group.members.length}} {{$t('members')}}</div>
					</div>
					<div class="settings-groups__remove-btn" @click="removeGroup(groupIndex)">{{$t('remove')}}</div>
				</div>
				<ul class="settings-groups__members">
					<li class="settings-groups__member" v-for="member, memberIndex in group.members">
						<div class="settings-groups__member-name-wrapper">
							<div class="settings-groups__member-avatar">
								<img :src="member.avatar" alt="">
							</div>
							<div class="settings-groups__member-name">{{member.name}}</div>
						</div>
						<div class="settings-groups__remove-btn" @click="removeMemberFromGroup(groupIndex, memberIndex)">{{$t('remove')}}</div>
					</li>
					<li class="settings-groups__add-member-wrapper">
						<div class="settings-groups__icon"></div>
						<div class="settings-groups__add-member-btn" @click="showAddMemberPopup()">{{$t('addMember')}}</div>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<Popup :visible.sync="showPopupAddMember">
		<select name="" id="">
			<option value="">sad</option>
			<option value="">sad</option>
		</select>
	</Popup>
</div>
</template>
<script>
	export default
	{
		data()
		{
			return {
				showPopupAddMember: false,
				groups: [
					{
						name: 'Administrators',
						members: [
							{name: 'Eugene Ford'},
							{name: 'Eugene Ford'},
							{name: 'Eugene Ford'},
						]
					}
				],
			}
		},
		methods:
		{
			removeGroup(groupIndex)
			{
				console.log(groupIndex);
			},
			removeMemberFromGroup(groupIndex, memberIndex)
			{
				console.log(groupIndex, memberIndex);
			},
			addGroup()
			{
				this.groups.push({name: 'New group', members: []});
			},
			showAddMemberPopup()
			{
				showPopupAddMember = true;
			},
			addMember(group)
			{
				group.members.push({name: 'new user'});
			},
			async getGroups()
			{
				await this.$store.dispatch('getGroups');
				this.groups = this.$store.state.groups.groups;
			}
		},
		mounted()
		{
			this.getGroups();
		}
	}
</script>
<style lang="scss">
	.settings-groups__icon
	{
		width: 9px;
		height: 9px;
		position: relative;
		margin-right: 7px;
		&:before,
		&:after
		{
			position: absolute;
			content: '';
			width: 100%;
			height: 1px;
			top: 50%;
			left: 0;
			background-color: #C2C7CF;
			transform: rotate(90deg);
		}
		&:before
		{
			transform: rotate(0deg);
		}
	}
	.settings-groups__add-member-wrapper
	{
		display: flex;
		align-items: center;
	}
	.settings-groups__add-member-btn
	{
		font-weight: normal;
		font-size: 11px;
		line-height: 13px;
		color: #677387;
	}
	.settings-groups__members
	{
		padding: 15px 0;
	}
	.settings-groups__member
	{
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 15px;
	}
	.settings-groups__member-name
	{
		font-weight: normal;
		font-size: 11px;
		line-height: 13px;
		color: #191C21;
	}
	.settings-groups__member-avatar
	{
		width: 15px;
		height: 15px;
		border-radius: 50%;
		overflow: hidden;
		margin-right: 6px;
		img
		{
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.settings-groups__member-name-wrapper
	{
		display: flex;
		align-items: center;
	}
	.settings-groups__head
	{
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		padding: 9px 0;
	}
	.settings-groups__group-name
	{
		font-weight: 500;
		font-size: 12px;
		line-height: 14px;
		color: #191C21;
		margin-right: 18px;
		input
		{
			border: none;
		}
		&.disabled
		{
			pointer-events: none;
		}
	}
	.settings-groups__member-info
	{
		font-weight: normal;
		font-size: 11px;
		line-height: 13px;
		color: rgba(103, 115, 135, 0.4);
	}
	.settings-groups__group-info
	{
		display: flex;
		align-items: center;
	}
	.settings-groups__group-head
	{
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1px solid rgba(103, 115, 135, 0.1);
		padding: 8px 0;
	}
	.settings-groups__wrapper
	{
		width: 630px;
	}
	.settings-groups__remove-btn
	{
		font-weight: normal;
		font-size: 11px;
		line-height: 13px;
		color: #191C21;
	}
	.settings-groups__content
	{
		padding-top: 29px;
	}
</style>