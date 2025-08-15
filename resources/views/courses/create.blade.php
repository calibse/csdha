<x-layout.user route="students.index" class="courses form" title="Add course">
    <article class="article">
        <form method="POST" action="{{ route('courses.store', [], false) }}" >
            @csrf
            <p>
                <label for="name">Course name</label>
                <input type="text" id="name" name="name" required maxlength="255">
            </p>
            <p>
                <label for="name">Acronym</label>
                <input type="text" id="acronym" name="acronym" required maxlength="8">
            </p>
            <p class="form-submit">
                <button type="submit">Save course</button>
            </p>
        </form>
        <article>
            <h2>All courses</h2>
            <ul class="item-list">
        @foreach ($courses as $course)
            <li class="item">
                <span class="content">
                    {{ $course->name }}
                </span>
                <span class="context-menu">
                    <button popovertarget="course-{{ $course->id }}">
                        Edit
                    </button>
                    <button popovertarget="course-{{ $course->id }}-delete">    
                        Delete
                    </button>
                </span>
            </li>
        @endforeach
            </ul>
            @foreach ($courses as $course)
                <dialog popover id="course-{{ $course->id }}">
                    <form method="POST"
                        action="{{ route('courses.update', ['course' => $course->id], false) }}"
                    >
                        @method ('PUT')
                        @csrf
                        <p>
                            <label for="name">Course name</label>
                            <input type="text"
                                id="name"
                                name="name"
                                maxlength="255"
                                required
                                value="{{ $course->name }}"
                            >
                        </p>
                        <p>
                            <label for="name">Acronym</label>
                            <input type="text"
                                id="acronym"
                                name="acronym"
                                maxlength="8"
                                required 
                                value="{{ $course->acronym }}"
                            >
                        </p>

                        <p class="form-submit">
                            <button type="button"
                                popovertarget="course-{{ $course->id }}"
                            >
                                Cancel
                            </button>
                            <button type="submit">Update</button>
                        </p>
                    </form>
                </dialog>
                <dialog popover id="course-{{ $course->id }}-delete">
                    <form method="POST"
                        action="{{ route('courses.destroy', ['course' => $course->id], false) }}"
                    >
                        @method ('DELETE')
                        @csrf
                        <p>
                            Are you sure you want to delete the course <strong>{{ $course->name }}</strong>?</p>
                        </p>
                        <p class="form-submit">
                            <button type="button"
                                popovertarget="course-{{ $course->id }}-delete"
                            >
                                Cancel
                            </button>
                            <button type="submit">Delete</button>
                        </p>
                    </form>
                </dialog>
            @endforeach
        </article>
    </article>
</x-layout.user>