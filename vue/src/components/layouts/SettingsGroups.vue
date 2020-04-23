<template>
	<div class="settings-groups__wrapper">
		<div class="settings-groups__head">
			<button @click="addGroup" class="el-btn">{{$t('createGroup')}}</button>
		</div>
		<div class="settings-groups__content">
			<ul class="settings-groups__groups-content">
				<li class="settings-groups__group-wrapper" v-for="group, groupIndex in $store.state.groups.groups">
					<div class="settings-groups__group-head">
						<div class="settings-groups__group-info">
							<div
								class="settings-groups__group-name"
								:class="{disabled: group.name == 'Administrators'}"
								contenteditable="true"
								@blur="changeGroupName($event,group)"
							>
								{{ group.name }}
							</div>
							<div class="settings-groups__member-info">{{group.members.length}} {{$t('members')}}</div>
						</div>
						<button v-if="group.name != 'Administrators'" class="settings-groups__remove-btn" @click="removeGroup(group)">{{$t('remove')}}</button>
					</div>
					<ul class="settings-groups__members">
						<li class="settings-groups__member" v-for="member, memberIndex in group.members">
							<div class="settings-groups__member-name-wrapper">
								<img class="settings-groups__avatar" :src="member.avatar" alt="">
								<div class="settings-groups__member-name">{{member.name}}</div>
							</div>
							<button class="settings-groups__remove-btn" @click="removeMemberFromGroup(groupIndex, memberIndex)">{{$t('remove')}}</button>
						</li>
						<li class="settings-groups__add-member-wrapper">
							<div class="settings-groups__icon"></div>
							<button class="settings-groups__add-member-btn" @click="showAddMemberPopup()">{{$t('addMember')}}</button>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<Popup class="settings-groups__add-popup" :visible.sync="showPopupAddMember">
			<input class="settings-groups__add-search" type="text" placeholder="Search for person">
			<div class="settings-groups__user-line">
				<img src="https://www.gravatar.com/avatar/28ff2f48655820e1aaf15687dc5e6be5?d=image.png&s=40" alt="">
				Morris Wilson
			</div>
			<div class="settings-groups__user-line">
				<img src="https://www.gravatar.com/avatar/28ff2f48655820e1aaf15687dc5e6be5?d=image.png&s=40" alt="">
				Courtney Mckinney
			</div>
			<div class="settings-groups__user-line">
				<img src="https://www.gravatar.com/avatar/28ff2f48655820e1aaf15687dc5e6be5?d=image.png&s=40" alt="">
				Mitchell Lane
			</div>
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
			}
		},
		methods:
		{
			async removeGroup(group)
			{
				await this.$store.dispatch('removeGroup',group.id);
			},
			removeMemberFromGroup(groupIndex, memberIndex)
			{
				console.log(groupIndex, memberIndex);
			},
			async addGroup()
			{
				await this.$store.dispatch('addGroup');
			},
			showAddMemberPopup()
			{
				this.showPopupAddMember = true;
			},
			addMember(group)
			{
				// group.members.push({name: 'new user'});
			},
			async getGroups()
			{
				await this.$store.dispatch('getGroups');
			},
			async changeGroupName(event,group)
			{
				await this.$store.dispatch('setGroupName',{
					id   : group.id,
					name : event.target.innerText
				});
				console.log(event.target.innerText);
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
		&:before, &:after
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
		&:before {transform: rotate(0deg); }
	}
	.settings-groups__add-member-wrapper
	{
		display: inline-flex;
		align-items: center;
	}
	.settings-groups__add-member-btn
	{
		font-weight: normal;
		font-size: 11px;
		line-height: 13px;
		color: #677387;
		border:0px;
		padding:0px;
		cursor: pointer;
		background: none;
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
	.settings-groups__avatar
	{
		width: 15px;
		height: 15px;
		border-radius: 50%;
		overflow: hidden;
		margin-right: 6px;
		object-fit: cover;
	}
	.settings-groups__member-name-wrapper
	{
		display: flex;

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
		&.disabled {pointer-events: none;}
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
		border:0px;
		padding:0px;
		cursor: pointer;
	}
	.settings-groups__content {padding-top: 29px; }
	.settings-groups__add-popup .popup-close{display: none;}
	.settings-groups__add-search
	{
		height: 43px;
		background: rgba(103, 115, 135, 0.1);
		border:0px;
		border-radius: 3px;
		width:100%;
		padding-left: 24px;
		font-size: 11px;
		margin-bottom: 15px;
		&::placeholder{color: rgba(103, 115, 135, 0.7);}
	}
	.settings-groups__user-line
	{
		display: flex;
		align-items: center;
		font-size:11px;
		color: #191C21;
		height: 30px;
		cursor: pointer;
		margin-left: -20px;
		margin-right: -20px;
		padding-left: 20px;
		img
		{
			width:15px;
			height: 15px;
			object-fit: cover;
			border-radius: 50%;
			margin-right: 7px;
		}
		&:hover{background: rgba(103, 115, 135, 0.1);}
	}
</style>