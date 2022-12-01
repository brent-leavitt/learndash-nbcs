<?php 

//all numbers below are arbitrarily assigned simply to represent that these are POST_IDs, and have no particluar sequence or order to them. 

$assignment_map = [
	7 => [ //track
		11 => [ //course, number represents the Post ID for the Course manual Material CPT. 
			13 => [ 12, 15, 23, 45 ], //section and list of assignments that pertain to that section.
			17 => [ 46, 55, 70, 78 ], //etc.				
		],  
		13 => [ //course
			33 => [ 12, 15, 23, 45 ], //section and list of assignments that pertain to that section.
			44 => [ 46, 55, 70, 78 ], //etc.				
		]
	],
	
	201 => [ //track
		234 => [ //course
			123 => [ 212, 216, 223, 245 ], //section and list of assignments that pertain to that section.
			435 => [ 246, 255, 270, 278 ], //etc.				
		], 
		346 => [ //course
			347 => [ 312, 315, 323, 345 ], //section and list of assignments that pertain to that section.
			456 => [ 446, 555, 470, 578 ], //etc.				
		]
	],
]

//Track is a CPT. 

//Make a metabox for the Track CPT that allows us to select which Courses are available and we would like to add to the Track.  DONE

//Because Courses and Sections both have nested content, they should allow for this nested content. Conversely, Content and Assignments should not have nested content and we should check and send warning message if such is the case. 

//All the assignment details: title, ID, status( current or deprecated), start_date, end_date, replaced_by. should be stored as post_metadata for the material CPT, and loaded from there into the assignment class. This doesn't need to be hardcoded into the options table of the database. 

/*
	-assignments	
		-ID (CPT-ID)
		-title (assignment name)
		-status (current or discontinued)
		-start_date
		-end_date
		-replaced_by (int or array - ids for assignments that replaced this asmt)
					
*/

//Grades class says look, here  is this student. They are signed up for these tracks. Those tracks have these courses (some can be duplicates, but each course is only represented once). These course have these assignments. Assignments are organized into sections and sometime subsections, indefinitely (though not presently).  

//Assignments could appear in multiple courses, maybe? But one assignment would never be in the same course or track or section more than once. And a student would never be required to complete the same assignment, section or course work more than once. 

//The purpose for the grades group of classes is for reporting purposes. So students and instructors can assess progress. 

//It also has a secondary purpose of managing assignments, overriding grade status on group level and recording information in the database. 

//NOTES FROM STUDYING THE ORIGINAL ASSIGNMENT MAP DOCUMENT

//The original assignment map was to handle an assignment's place in the overall course. It was designed to be evolving, but not really. It actually permenantly fixed assignment into their assigned locations and did move ever. 

//Connecting Submitted user assignments to the course assignments, there is only two ID's needed. User Submitted Assignment, and Course created assignments. Every student should have a list of created work stored in the user_metadata called student_grades. These are not connected to anything else. 


database: 
	- Options Table: Assignments_Map //Master Document, Much more simplified.
	
	- User Meta Table: 
		- student_grades
		- student_tracks
		
	- Post Meta Table: 
		- (tracks) track_courses
		- (assignment) material_deprecated (date value)
		- (assignment) replaced_by (INT value)
		- 
	