<?php

// Home
//Breadcrumbs::for('home', function ($trail) {
//    $trail->push('Home', route('user.home'));
//});

// Courses
Breadcrumbs::for('courses', function ($trail) {
    $trail->push('My Courses', route('course.index'));

    $crumbs = session()->get('crumbs', []);
    $current = Route::current()->getName();
    if ($current == 'course.index') # Restart $crumbs
        session()->put('crumbs', [$current]);
    elseif ($current == 'session.index') # Restart $crumbs
        session()->put('crumbs', ['course.index', 'course.view', $current]);
    elseif (last($crumbs) != $current) # Push to $current
        session()->push('crumbs', $current);
});

// Courses > Show [Course]
Breadcrumbs::for('course.show', function ($trail, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

//    TODO: convert to PHP's end($crumbs);
    if (last($crumbs) == 'session.active' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'session.active'))
        $trail->push('Active Sessions', route('session.active'));

    $trail->push('Course ' . $course->code, route('course.view', $course));
});

// Courses > Show [Course] > Add Student
Breadcrumbs::for('course.add-student', function ($trail, $user, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Add Students', route('course.add-student', $course));
});

// Courses > ? > Edit [Course]
Breadcrumbs::for('course.edit', function ($trail, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Edit Course ' . $course->code, route('course.edit', $course));
});

// Create Course
Breadcrumbs::for('course.create', function ($trail) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    $trail->push('Create Course', route('course.create'));
});

// Create Session
Breadcrumbs::for('session.create', function ($trail, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Create Session', route('course.create'));
});

// Edit Session
Breadcrumbs::for('session.edit', function ($trail, $session) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'session.active' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'session.active'))
        $trail->push('Active Sessions', route('session.active'));

    $trail->push('Edit Session', route('session.edit', $session));
});

// Courses > ? > Sessions of [CCPX]
Breadcrumbs::for('session.index', function ($trail, $sessions, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Sessions', route('session.index', $course->id));
});

// ? > Active Sessions
Breadcrumbs::for('session.active', function ($trail, $sessions) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

//    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
//        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Active Sessions', route('session.active', $sessions));
});

// ? > Add Form
Breadcrumbs::for('form.create', function ($trail) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    $trail->push('Create Form', route('form.create'));
});

// ? <?MY_FORMS> > Edit Form <FORM>
Breadcrumbs::for('form.edit', function ($trail, $form) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'form.index' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'form.index'))
        $trail->push('My Forms', route('form.index'));

    $trail->push('Edit Form ' . $form->id, route('form.edit', $form));
});

// ? > My Forms
Breadcrumbs::for('form.index', function ($trail) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    $trail->push('My Forms', route('form.index'));
});

//// Home > Blog > [Category]
//Breadcrumbs::for('category', function ($trail, $category) {
//    $trail->parent('blog');
//    $trail->push($category->title, route('category', $category->id));
//});
//
//// Home > Blog > [Category] > [Post]
//Breadcrumbs::for('post', function ($trail, $post) {
//    $trail->parent('category', $post->category);
//    $trail->push($post->title, route('post', $post->id));
//});
