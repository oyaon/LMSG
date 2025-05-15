import os
from flask import Flask, render_template, redirect, url_for, flash, request
from flask_login import LoginManager, login_user, logout_user, current_user, login_required

from .models import db, User, Course, Enrollment, Lesson
from .forms import LoginForm, RegistrationForm, CourseForm, LessonForm

BASE_DIR = os.path.abspath(os.path.dirname(__file__))

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your_super_secret_key_12345' # IMPORTANT: Change this in production!

# Configure the SQLite database path to be in an 'instance' folder next to app.py
instance_dir = os.path.join(BASE_DIR, 'instance')
if not os.path.exists(instance_dir):
    os.makedirs(instance_dir)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///' + os.path.join(instance_dir, 'lms.db')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db.init_app(app)

login_manager = LoginManager()
login_manager.init_app(app)
login_manager.login_view = 'login' # Route name for the login page
login_manager.login_message_category = 'info'

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

# --- Routes ---

@app.route('/')
def index():
    courses = Course.query.order_by(Course.title).all()
    return render_template('index.html', courses=courses, title='Home')

@app.route('/register', methods=['GET', 'POST'])
def register():
    if current_user.is_authenticated:
        return redirect(url_for('index'))
    form = RegistrationForm()
    if form.validate_on_submit():
        user = User(username=form.username.data, email=form.email.data, is_instructor=form.is_instructor.data)
        user.set_password(form.password.data)
        db.session.add(user)
        db.session.commit()
        flash('Congratulations, you are now a registered user! Please log in.', 'success')
        return redirect(url_for('login'))
    return render_template('register.html', title='Register', form=form)

@app.route('/login', methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('index'))
    form = LoginForm()
    if form.validate_on_submit():
        user = User.query.filter_by(email=form.email.data).first()
        if user and user.check_password(form.password.data):
            login_user(user, remember=form.remember_me.data)
            flash(f'Welcome back, {user.username}!', 'success')
            next_page = request.args.get('next')
            return redirect(next_page) if next_page else redirect(url_for('dashboard'))
        else:
            flash('Login Unsuccessful. Please check email and password.', 'danger')
    return render_template('login.html', title='Login', form=form)

@app.route('/logout')
@login_required
def logout():
    logout_user()
    flash('You have been logged out.', 'info')
    return redirect(url_for('index'))

@app.route('/dashboard')
@login_required
def dashboard():
    enrolled_courses = []
    created_courses = []
    if current_user.is_instructor:
        created_courses = Course.query.filter_by(instructor_id=current_user.id).order_by(Course.title).all()
    else:
        enrollments = Enrollment.query.filter_by(user_id=current_user.id).all()
        enrolled_courses = [enrollment.course for enrollment in enrollments]
        # Sort enrolled_courses by title if needed, e.g., enrolled_courses.sort(key=lambda c: c.title)
    return render_template('dashboard.html', title='Dashboard', 
                           enrolled_courses=enrolled_courses, 
                           created_courses=created_courses)

@app.route('/course/new', methods=['GET', 'POST'])
@login_required
def create_course():
    if not current_user.is_instructor:
        flash('Only instructors can create courses.', 'warning')
        return redirect(url_for('index'))
    form = CourseForm()
    if form.validate_on_submit():
        course = Course(title=form.title.data, description=form.description.data, instructor_id=current_user.id)
        db.session.add(course)
        db.session.commit()
        flash('Your course has been created!', 'success')
        return redirect(url_for('course_detail', course_id=course.id))
    return render_template('create_course.html', title='Create Course', form=form)

@app.route('/course/<int:course_id>')
def course_detail(course_id):
    course = Course.query.get_or_404(course_id)
    is_enrolled = False
    if current_user.is_authenticated and not current_user.is_instructor:
        is_enrolled = Enrollment.query.filter_by(user_id=current_user.id, course_id=course.id).first() is not None
    lessons = Lesson.query.filter_by(course_id=course.id).order_by(Lesson.order).all()
    return render_template('course_detail.html', title=course.title, course=course, lessons=lessons, is_enrolled=is_enrolled)

@app.route('/course/<int:course_id>/enroll', methods=['POST'])
@login_required
def enroll_course(course_id):
    course = Course.query.get_or_404(course_id)
    if current_user.is_instructor:
        flash('Instructors cannot enroll in courses.', 'warning')
        return redirect(url_for('course_detail', course_id=course_id))
    
    if Enrollment.query.filter_by(user_id=current_user.id, course_id=course_id).first():
        flash('You are already enrolled in this course.', 'info')
    else:
        enrollment = Enrollment(user_id=current_user.id, course_id=course_id)
        db.session.add(enrollment)
        db.session.commit()
        flash(f'You have successfully enrolled in "{course.title}"!', 'success')
    return redirect(url_for('course_detail', course_id=course_id))

@app.route('/course/<int:course_id>/lesson/add', methods=['GET', 'POST'])
@login_required
def add_lesson(course_id):
    course = Course.query.get_or_404(course_id)
    if course.instructor_id != current_user.id:
        flash('You are not authorized to add lessons to this course.', 'danger')
        return redirect(url_for('course_detail', course_id=course.id))
    
    form = LessonForm()
    if form.validate_on_submit():
        last_lesson = Lesson.query.filter_by(course_id=course.id).order_by(Lesson.order.desc()).first()
        new_order = (last_lesson.order + 1) if last_lesson else 1
        lesson = Lesson(title=form.title.data, content=form.content.data, course_id=course.id, order=new_order)
        db.session.add(lesson)
        db.session.commit()
        flash('New lesson added successfully!', 'success')
        return redirect(url_for('course_detail', course_id=course.id))
    return render_template('add_lesson.html', title='Add Lesson', form=form, course=course)

@app.route('/lesson/<int:lesson_id>')
@login_required
def view_lesson(lesson_id):
    lesson = Lesson.query.get_or_404(lesson_id)
    course = lesson.course
    is_enrolled = Enrollment.query.filter_by(user_id=current_user.id, course_id=course.id).first() is not None
    if not (is_enrolled or current_user.id == course.instructor_id):
        flash('You must be enrolled in the course or be the instructor to view this lesson.', 'warning')
        return redirect(url_for('course_detail', course_id=course.id))
    return render_template('view_lesson.html', title=lesson.title, lesson=lesson, course=course)

# --- CLI commands ---
@app.cli.command('init-db')
def init_db_command():
    """Creates the database tables."""
    with app.app_context(): # Ensure operations are within app context
        db.create_all()
    print('Initialized the database.')

if __name__ == '__main__':
    app.run(debug=True) # For development only