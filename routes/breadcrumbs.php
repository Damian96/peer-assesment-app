<?php

// Home
//Breadcrumbs::for('home', function ($trail) {
//    $trail->push('Home', route('user.home'));
//});

// Courses
Breadcrumbs::for('courses', function ($trail) {
    $trail->push('My Courses', route('course.index'));
});

// Courses > Show [Course]
Breadcrumbs::for('course.show', function ($trail, $course) {
    $trail->parent('courses');
    $trail->push('Course ' . $course->code, route('course.view', $course));
});

// Courses > Show [Course] > Add Student
Breadcrumbs::for('course.add-student', function ($trail, $user, $course) {
    $trail->parent('course.show', $course);
    $trail->push('Add Student to ' . $course->code, route('course.add-student', $course));
});

// Courses > Edit [Course]
Breadcrumbs::for('course.edit', function ($trail, $course) {
    $trail->parent('courses');
    $trail->push('Edit Course ' . $course->code, route('course.edit', $course));
});

// Courses > [Sessions]
Breadcrumbs::for('session.index', function ($trail, $sessions, $course) {
    $trail->parent('course.show', $course);
    $trail->push('Sessions', route('session.index', $sessions, $course));
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
