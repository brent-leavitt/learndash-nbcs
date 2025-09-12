# LearnDash Integration Documentation

## Overview
Our training platform uses LearnDash as the content delivery system with significant customization to support our essay-based assignment workflow and trainer-mediated evaluation process.

## Content Structure Mapping

### LearnDash Terminology Remapping
- **LearnDash Course** → **Our Program** (represents complete certification training)
- **LearnDash Lesson** → **Our Unit** (major content sections within a program)
- **LearnDash Topic** → **Our Content** (individual instructional pieces)

### Structural Organization
- **Programs** (LearnDash Courses): Top-level certification training programs
- **Section Headings** (LearnDash Section Headings): Visual grouping of Units into general sections (historically called "courses" in our business)
- **Units** (LearnDash Lessons): Individual instructional sections within each program
- **Content** (LearnDash Topics): Individual instructional content pieces, some with assignments

### Content Classification
- **Instructional Content**: Educational materials without assignments
- **Assignment Content**: Instructional materials with integrated custom assignments
  - Manually labeled in content title for identification
  - LearnDash assignment upload option enabled in settings
  - Custom template overrides default LearnDash assignment functionality

## Assignment Integration Architecture

### Assignment Implementation Strategy
- **Primary System**: Custom Assignment CPT (90%+ of assignments)
- **Secondary System**: Small number of LearnDash quizzes (not integrated with main system)
- **Integration Point**: LearnDash assignment upload feature hijacked to load custom assignment editor

### Template Override System
```
LearnDash Assignment Upload → Template Override → Custom Assignment Editor
```

### Content-Assignment Relationships
- **Relationship**: Arbitrary - admin choice to enable/disable per content item
- **Not Required**: Not all content has assignments
- **Manual Configuration**: Assignment connection toggled via LearnDash settings tab per content item
- **Editor Integration**: Custom assignment editor loads in place of default LearnDash assignment upload

## Enrollment & Access Control

### Course Enrollment Method
- **Primary System**: Restricted Content Pro (RCP) handles all enrollment
- **LearnDash Setting**: Courses marked as "Open" (bypass LearnDash enrollment system)
- **Exception**: Certificate Renewal program marked as "Free" with manual access control
- **No Usage**: LearnDash Groups not implemented
- **No Usage**: Content dripping and prerequisites not implemented

### Access Control Flow
```
RCP Membership Status → WordPress User Role → LearnDash Content Access
```

## Progress Tracking (Dual System)

### LearnDash Progress System
- **Purpose**: Track overall program completion through all content
- **Student Control**: Students can mark non-assignment content as complete
- **Trainer Control**: Only trainers can mark assignment-containing content as complete
- **Scope**: Covers all content items (with and without assignments)

### Custom Assignment Progress System
- **Purpose**: Track assignment-specific completion status
- **Scope**: Assignment completion only (separate from content completion)
- **Control**: Based on trainer evaluation of assignment status (Complete/Incomplete)
- **Independence**: Operates separately from LearnDash progress system

### Progress Control Matrix
| Content Type | Student Can Mark Complete (LearnDash) | Admin/Trainer Can Control Assignment Status (Custom CPT) |
|--------------|---------------------------------------|--------------------------------------------------------|
| Without Assignment | ✅ | N/A |
| With Assignment | ❌ | ✅ (Complete/Incomplete) |

**Key Point**: Trainers and Admins control assignment completion through Custom Assignment CPT editor only. Assignment status changes should sync to LearnDash content completion (currently broken).

## User Role Integration

### LearnDash Role Usage
- **Not Used**: LearnDash student roles
- **Not Used**: LearnDash group leader roles
- **Not Used**: LearnDash instructor roles

### WordPress Role Integration
- **System**: Custom WordPress roles (Reader, Student, Alumni) control access
- **Trainer Role**: Custom trainer role not integrated with LearnDash system
- **Access Control**: WordPress roles determine LearnDash content visibility

## Certificate & Completion System

### LearnDash Certificate System
- **Status**: Not implemented
- **Reason**: Manual certification process via trainers doesn't align with automated LearnDash certificates

### Completion Calculation
- **LearnDash**: Does not calculate course completion based on custom assignment system
- **Custom System**: Assignment completion tracked independently
- **Certification Trigger**: Manual process based on trainer evaluation, not LearnDash completion status

## Current Issues & Development Priorities

### Critical Bug: Content Completion Sync
- **Issue**: Content not marked as completed when trainer marks assignment as completed
- **Expected Behavior**: Assignment Complete status should auto-complete associated LearnDash content
- **Reverse Issue**: Content should be marked incomplete when assignment marked incomplete
- **Impact**: Progress tracking disconnect between systems

### Sync Requirements (NEEDED)
```
Assignment Status: Complete → LearnDash Content: Complete
Assignment Status: Incomplete → LearnDash Content: Incomplete
```

## Future Multi-Program Considerations

## Future Multi-Program Architecture

### Program Separation Strategy
- **Structure**: Multiple separate LearnDash courses (programs)
- **Isolation**: Each program will be its own LearnDash course entity
- **Membership Mapping**: RCP program-specific memberships will control access to corresponding LearnDash courses
- **Content Organization**: Current single-program structure will need to be replicated for each new program

### Content Access Matrix (Future State)
| User | Program A Membership | Program B Membership | LearnDash Access |
|------|---------------------|---------------------|------------------|
| John | Student | Reader | Course A: Full, Course B: Read-only |
| Jane | Alumni | None | Course A: Read-only, Course B: Hidden |
| Bob | None | Student | Course A: Hidden, Course B: Full |

### Migration Considerations
- **Current State**: Single LearnDash course structure
- **Future State**: Multiple LearnDash courses for different programs
- **Assignment Mapping**: `nbcs_assignments_map` will need to handle cross-program assignment identification
- **Template Overrides**: May need program-specific template logic

## Integration Points Summary

### RCP → LearnDash Flow
1. User subscribes to program via RCP
2. RCP assigns WordPress role
3. WordPress role grants LearnDash content access
4. User accesses program content through LearnDash interface

### Custom Assignment → LearnDash Flow (CURRENTLY BROKEN)
1. Student submits assignment via custom editor (in LearnDash content)
2. Trainer evaluates assignment in custom Assignment CPT editor screen (outside LearnDash)
3. Trainer marks assignment complete/incomplete in CPT editor
4. **MISSING**: Assignment status should trigger LearnDash content completion update
5. **MISSING**: LearnDash progress should reflect assignment evaluation
6. **Current Result**: LearnDash content remains incomplete despite assignment completion

### LearnDash → Custom Assignment Flow
1. Student navigates to assignment content in LearnDash
2. LearnDash assignment upload area loads custom assignment editor
3. Student works with custom Assignment CPT
4. Progress tracked in both systems (should be synced)

## Technical Implementation Details

### Template Override Architecture
- **Theme**: Custom child theme "nb-learn" (extends Pinnacle theme)
- **Method**: Direct LearnDash template file overrides in child theme
- **Function Calls**: Overridden templates call custom functions from "learndash-nbcs" plugin
- **Integration**: Child theme templates → Custom plugin functions → Assignment CPT editor

### Content-Assignment Mapping System
- **Storage**: WordPress options table
- **Meta Key**: `nbcs_assignments_map`
- **Data Structure**: Array of LearnDash content IDs that have assignments attached
- **Primary Purpose**: Track assignment completion status for custom system (predates LearnDash integration)
- **Status Tracking**: Provides independent assignment status management separate from LearnDash
- **Manual Status Updates**: Allows assignment completion to be marked manually without assignment editor submission
- **Alternative Submission Support**: Accommodates assignments submitted via email, mail, or other non-digital methods
- **ID-Based System**: Uses LearnDash content IDs only (not Assignment CPT IDs or manual titles)

### Trainer Workflow Separation
- **LearnDash Access**: Trainers do not have access to LearnDash student interface
- **Assignment Control**: Trainers work exclusively through custom Assignment CPT editor screens
- **Progress Impact**: Assignment status changes in CPT editor should trigger LearnDash content completion updates
- **Current Gap**: Assignment completion not syncing to LearnDash content completion