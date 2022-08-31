@props([
'title',
'role'
])

<div x-data x-init="
     document.title = @js(
                            (
                                isset($title) 
                                    ? (
                                        $title.(
                                            isset($role) ? (' | '.$role) : ''
                                        )
                                    ).' | '
                                    : ''
                            ).config('app.name')
                        )"></div>