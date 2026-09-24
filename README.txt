# UMBC Tutor Availability Status System

A web-based tutor availability and session-management system developed for the University of Maryland, Baltimore County (UMBC) Academic Success Center as a CMSC 447 Software Engineering project.

The system gives students a simple public view of tutor availability while providing authorized staff with an administrative interface for managing tutor sessions and live status information.

## Project Overview

The existing tutoring workflow required a clearer way for students to see whether tutors were currently available and for staff to maintain session information.

Our team designed and developed a WordPress-based system with two primary interfaces:

- **Public Tutor Availability Page** for students
- **Admin Control Center** for authorized staff

The project followed a three-tier web application design:

**Frontend → Application Logic → Data Storage**

## Key Features

### Student-Facing Interface

- Displays tutor sessions organized by subject and course
- Shows live tutor availability status
- Supports statuses such as:
  - Checked In
  - Not Checked In
  - Cancelled
  - Left Early
- Displays early-leave information when applicable
- Uses expandable subject and course sections to reduce scrolling
- Provides a responsive layout for desktop and mobile users
- Displays update information to help users understand data freshness

### Admin Control Center

- Provides authenticated access for authorized staff
- Search and filter tutor sessions by:
  - Tutor
  - Course
  - Subject
  - Day
- Allows staff to update tutor availability
- Supports notes and early-leave time information
- Synchronizes related course cards belonging to the same real tutor shift
- Supports CSV-based creation of tutor sessions
- Maintains audit information for administrative updates
- Includes automatic daily reset behavior for session statuses

## Security and Validation

The application includes several defensive controls appropriate for a WordPress-based administrative system:

- Authentication and authorization checks
- WordPress capability validation
- Nonce validation for protected requests
- Server-side input validation
- Input sanitization
- Output handling designed to reduce XSS risk
- Restricted administrative actions
- Audit metadata for updates

## Technologies

- PHP
- WordPress
- HTML
- CSS
- JavaScript
- WordPress Custom Post Types
- Advanced Custom Fields-compatible data storage
- WordPress post metadata
- Git
- Jira

## Software Engineering Process

The project was developed using an Agile/Scrum-style workflow with iterative requirements, implementation, sponsor feedback, and testing.

Engineering artifacts created during the project included:

- Software Requirements Specification (SRS)
- Software Design Document (SDD)
- User Interface Design Document (UIDD)
- UML diagrams
- Use cases
- Sequence and activity diagrams
- Data models
- Requirements traceability
- Formal testing documentation

## Testing

The team performed specification-based black-box testing across the major system use cases.

A total of **41 test cases** covered areas including:

- Public tutor display
- Filtering and search
- Administrative editing
- Authentication and access control
- Shared-shift synchronization
- CSV tutor-session creation
- Logout behavior
- Daily reset behavior
- Audit metadata
- Mobile display behavior

Testing also identified known limitations and potential production improvements, including duplicate CSV-entry handling and WordPress scheduled-task timing.

## Architecture

The system follows a web-based three-tier structure:

```text
Student / Administrator
          |
          v
   Frontend Interface
  HTML / CSS / JavaScript
          |
          v
 Application / Server Logic
       WordPress / PHP
          |
          v
      Data Layer
WordPress Post Metadata /
Custom Session Records
