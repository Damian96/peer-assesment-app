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

// Courses > ? > Sessions of [CCPX]
Breadcrumbs::for('session.index', function ($trail, $sessions, $course) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Sessions', route('session.index', $sessions, $course));
});

// ? > Active Sessions
Breadcrumbs::for('session.active', function ($trail, $sessions) {
    $crumbs = session()->get('crumbs', []);
    $trail->parent('courses');

//    if (last($crumbs) == 'course.view' || (count($crumbs) >= 2 && $crumbs[count($crumbs) - 2] == 'course.view'))
//        $trail->push('Course ' . $course->code, route('course.view', $course));

    $trail->push('Active Sessions', route('session.active', $sessions));
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
