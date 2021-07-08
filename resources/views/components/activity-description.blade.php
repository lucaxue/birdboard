@props(['activity'])

@switch($activity->description)
	@case('created_project')
		{{ $activity->user->name }} created a project
		@break
	@case('updated_project')
		@if(count($activity->changes['after']) === 1)	
			{{ $activity->user->name }} updated the {{ key($activity->changes['after']) }}
		@else
			{{ $activity->user->name }} updated the project
		@endif
		@break
	@case('created_task')
		{{ $activity->user->name }} added "{{ $activity->subject->body }}"
		@break
	@case('completed_task')
		{{ $activity->user->name }} completed "{{ $activity->subject->body }}"
		@break
	@case('incompleted_task')
		{{ $activity->user->name }} incompleted "{{ $activity->subject->body }}"
		@break
	@case('deleted_task')
		{{ $activity->user->name }} deleted "{{ $activity->subject->body }}"
		@break
	@default
		Invalid activity
@endswitch