/* Query Number 1 */
SELECT DISTINCT student.name FROM student 
    JOIN takes ON student.id = takes.id 
    WHERE takes.course_id LIKE 'CS%';

/* Query Number 2 */
SELECT DISTINCT student.name, student.ID FROM student 
    JOIN takes ON student.id = takes.id 
    WHERE takes.grade LIKE 'F%';

/* Query Number 3 */
SELECT dept_name, max(salary) FROM instructor 
    GROUP BY dept_name;

/* Query Number 4 */
SELECT DISTINCT course.title, student.name FROM student
    JOIN takes ON takes.ID = student.ID
    JOIN section ON section.course_id = takes.course_id
    JOIN course ON course.course_id = section.course_id
    JOIN time_slot ON section.time_slot_id = time_slot.time_slot_id
    WHERE time_slot.day LIKE 'F'
    AND time_slot.start_hour > 12
    AND takes.course_id IN
    (SELECT course_id FROM takes
    GROUP BY course_id HAVING count(ID) >= 2 );

/* Query Number 5 */
SELECT DISTINCT instructor.name, teaches.course_id FROM instructor 
    JOIN teaches ON teaches.ID = instructor.ID 
    JOIN section ON section.course_id = teaches.course_id 
    JOIN classroom ON classroom.building = section.building
    AND classroom.room_no = section.room_no 
    WHERE classroom.capacity > 50;