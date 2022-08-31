@if (session()->has('notif_alert'))
@php
$notif = session()->get('notif_alert');
@endphp
<script type="text/javascript">
	var notifAlert = {!! json_encode(session()->get('notif_alert')) !!};
</script>
<input type="hidden" name="notif" class="notif-alert" value="{!! $notif['message'] !!}"
	data-type="{{ $notif['type'] }}">
@endif