## Integration Points

### LearnDash LMS (Current & Future)
- **Current**: Course and lesson structure for content delivery
- **Current**: Integration with custom assignment system
- **Current**: User progress tracking
- **Current**: Certificate management (manual issuance)
- **Future**: Program-specific course access control
- **Future**: Per-program progress tracking and reporting

### Restricted Content Pro (RCP) - Needs Enhancement
- **Current**: Subscription management for three global tiers
- **Current**: Payment processing and billing
- **Current**: Access control based on subscription status
- **Current**: Integration with WordPress user roles
- **Needed**: Program-specific membership management
- **Needed**: Membership conflict prevention
- **Needed**: Automatic subscription upgrade/downgrade handling
- **Needed**: Duplicate subscription prevention

### Custom Plugin (Current & Development)
- **Current**: Assignment post type and status management
- **Current**: Extended TinyMCE editor with file upload
- **Current**: Trainer workflow tools
- **Current**: User role transition automation
- **Current**: Basic access control enforcement
- **Development**: Program-specific access control
- **Development**: Membership-based permission system
- **Development**: Trainer quota and assignment distribution
- **Development**: Payment conflict resolution# Business Logic Documentation

## Overview
Pay-as-you-go online training program with essay-based assignments and manual certification process. All content is behind paywall with three distinct access levels.

## Program-Specific Membership System (IDEAL STATE)

### Membership Matrix Structure
Each training program has three corresponding membership levels:
- **Program Reader** ($10/month USD per program)
- **Program Student** ($30/month USD per program) 
- **Program Alumni** ($50/2 years per program after initial free period)

### Multi-Program Enrollment Rules
- **Allowed**: User can enroll in multiple different programs simultaneously
- **Allowed**: User can have different membership levels across different programs
- **Prohibited**: User cannot hold multiple memberships within the same program
- **Example**: User can be Student in Program A + Reader in Program B + Alumni in Program C

### Access Control (Program-Specific)

#### Program Reader ($10/month USD per program)
- **Purpose**: Read-only access to specific program materials
- **Permissions**: 
  - View course content for subscribed program only
  - No assignment submission capabilities
  - No access to submitted assignments
- **Payment**: Monthly subscription per program
- **Cancellation**: Can pause/cancel without penalty

#### Program Student ($30/month USD per program)
- **Purpose**: Active training participation with live trainer access for specific program
- **Permissions**:
  - Full access to subscribed program materials
  - Create, edit, and submit assignments for subscribed program
  - Access to live trainer support for subscribed program
- **Payment**: Monthly subscription per program
- **Cancellation**: Can pause/cancel without penalty
- **Progression**: Upon completion, manually awarded certificate by trainer and upgraded to Program Alumni status

#### Program Alumni ($50/2 years per program after initial free period)
- **Purpose**: Certified graduates maintaining access to specific program materials
- **Permissions**:
  - Read-only access to completed program materials
  - Access to view their own previously submitted assignments for that program
  - No new assignment submission capabilities
- **Payment Structure**:
  - First 2 years: FREE (post-certification for that program)
  - Renewal: $50 every 2 years per program
- **Special Notes**: Payment is part of certification renewal process for specific program

## User Roles & WordPress Integration (CURRENT vs IDEAL)

### Current Implementation (Role-Based)
- **Global Roles**: Reader, Student, Alumni apply to all content
- **Access Control**: Based on user role only, not program-specific memberships
- **Limitation**: Role-based access grants access to all programs at that level

### Ideal Implementation (Membership-Based)
- **Program-Specific Memberships**: Each program has separate Reader/Student/Alumni memberships
- **User Profile**: Users can hold different membership levels across multiple programs
- **Access Control**: Based on specific program membership, not global role
- **Multiple Programs**: Users can be enrolled in multiple programs simultaneously

### Role vs Membership Matrix (IDEAL)
| User | Program A | Program B | Program C | Access Rights |
|------|-----------|-----------|-----------|---------------|
| John | Student   | Reader    | Alumni    | Full A, Read B, Read C + Own Assignments C |
| Jane | Alumni    | Student   | -         | Read A + Own Assignments A, Full B |
| Bob  | Reader    | Reader    | Student   | Read A, Read B, Full C |

### Membership Conflict Prevention (IDEAL)
- **Same Program**: User cannot hold Reader + Student memberships for Program A
- **Cross Program**: User can hold Student in Program A + Reader in Program B
- **Upgrade/Downgrade**: Higher level membership automatically cancels lower level in same program
- **Role Inheritance**: User's effective role per program is their highest membership level in that program

## Assignment System (Custom Implementation)

### Assignment Post Type
- **Custom Post Type**: "Assignment"
- **Editor**: Extended WordPress TinyMCE with document upload capability
- **File Attachments**: Supported at assignment completion

### Assignment Workflow Rules
- **Submission Order**: Students can submit assignments in any order or work on multiple simultaneously
- **No Prerequisites**: Currently no assignment dependencies (future consideration for certificate renewal)
- **Student Autonomy**: Students control their own pacing through the certification process

### Assignment Statuses (Custom Post Status)
1. **Draft**: Student working on assignment, not yet submitted
2. **Submitted**: Student has completed and submitted for review
3. **Incomplete**: Trainer has reviewed and requires revision - student can continue editing
4. **Complete**: Trainer has approved the assignment - student can no longer edit

### Status Control Rules
- **Students Can**: Change Draft → Submitted, edit Incomplete assignments
- **Students Cannot**: Mark assignments as Complete, change Complete → Incomplete
- **Trainers Can**: Change Submitted → Incomplete/Complete, update instructor status
- **System Rule**: Once Complete, assignment is locked from student editing

### Instructor Status (Custom Meta Field)
Tracks trainer's interaction with each assignment:
1. **Unseen**: Trainer has not yet viewed the assignment
2. **Seen but not graded**: Trainer has opened but not yet evaluated
3. **Graded**: Trainer has completed evaluation and provided feedback

### Trainer Assignment System
- **Student-Trainer Pairing**: Students are assigned to specific trainers
- **Rotation Logic**: System rotates assignments between available trainers
- **Future Development**: Implementing trainer quota system based on program capacity
- **Assignment Scope**: Trainers only see assignments for their assigned students

## Access Control Rules

### Content Access Matrix
| User Type | Course Materials | Create Assignments | Submit Assignments | View Own Assignments |
|-----------|------------------|-------------------|-------------------|---------------------|
| Reader    | ✅ Read-only     | ❌                | ❌                | ❌                  |
| Student   | ✅ Full access   | ✅                | ✅                | ✅                  |
| Alumni    | ✅ Read-only     | ❌                | ❌                | ✅                  |

### Subscription Status Rules
- **Active Subscription**: Full access according to user role
- **Expired/Cancelled**: No access to any content
- **No Grace Period**: Access terminates immediately upon non-payment

## Certification Process

### Requirements
- Student must complete all required assignments
- All assignments must have "Complete" status
- Manual review and approval by trainer required

### Certification Workflow
1. Student completes final assignment
2. Trainer reviews all student work
3. Trainer manually issues certificate
4. User role changed from Student to Alumni
5. Alumni billing cycle begins (2 years free, then $50/2 years)

## Training Delivery Model

### Content Structure
- All training materials delivered through LearnDash LMS
- Essay-based learning approach
- No automated assessments or quizzes
- Human-evaluated assignments only

### Trainer Responsibilities
- Review and grade submitted assignments for assigned students only
- Update assignment statuses (incomplete/complete)
- Update instructor status (unseen/seen/graded)
- Manual certificate issuance upon program completion
- Live support for assigned active students
- Work within assigned student quota limits (future implementation)

## Business Rules Summary

1. **No Free Content**: Everything behind paywall
2. **No Trials**: Users must subscribe to access any content
3. **Flexible Subscriptions**: Students can pause/cancel without penalty
4. **Manual Certification**: No automated certificate issuance
5. **Role-Based Access**: Strict permissions based on subscription level
6. **Assignment-Centric**: Core learning through essay submissions
7. **Trainer-Mediated**: All evaluation done by human trainers
8. **Immediate Access Control**: Payment status directly controls access

## Current System Limitations & Development Priorities

### Known Issues (Current Implementation)

#### Multiple Membership Problem
- **Issue**: Users can sign up for multiple memberships of the same type for the same program
- **Issue**: Users can hold different membership levels for the same program simultaneously
- **Business Rule Violation**: Should be prevented - only one membership per program per user
- **Impact**: Billing complications and access confusion

#### Membership Downgrade Problem
- **Issue**: When user downgrades (Student → Reader), higher level membership remains active
- **Expected Behavior**: Higher level membership should be automatically cancelled
- **Current Behavior**: User maintains both memberships and may be double-billed
- **Impact**: Revenue loss and customer billing disputes

#### Payment Failure & Duplicate Subscription Problem
- **Scenario**: User's payment fails → User manually creates new subscription → Original payment retries successfully
- **Result**: User has two active subscriptions and is double-billed
- **Additional Issue**: If original subscription fails after retries, user role becomes inactive despite having active second subscription
- **Business Rule Violation**: User should maintain access with active subscription regardless of failed subscription status

#### Role-Based vs Membership-Based Access
- **Current**: Access control based on global user roles (affects all programs)
- **Ideal**: Access control based on program-specific memberships
- **Gap**: Cannot restrict access to specific programs - role grants access to all content at that level

### Under Development

#### Program-Specific Membership System
- Implementing individual memberships per program
- Developing membership conflict prevention logic
- Building automatic membership upgrade/downgrade workflows
- Creating program-specific access control mechanisms

#### Payment & Subscription Management
- Duplicate subscription prevention
- Automatic higher-level membership cancellation on downgrade
- Failed payment handling that doesn't affect other active subscriptions
- Payment retry logic that considers all user subscriptions

#### Trainer Quota System
- Implementing trainer capacity limits per program
- Developing student assignment distribution logic
- Building trainer workload balancing system

### Future Considerations
- Assignment prerequisites for certificate renewal
- Advanced reporting and analytics per program
- Bulk enrollment capabilities for corporate accounts
- Integration with external assessment tools