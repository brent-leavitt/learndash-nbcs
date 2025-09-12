# Restricted Content Pro (RCP) Integration Documentation

## Overview
RCP serves as the primary subscription management and payment processing system, handling user enrollment, billing, and WordPress role assignment. The system currently uses global membership levels but needs to evolve to support program-specific memberships.

## Current Membership Structure

### Membership Levels (Global)
- **Reader** - $10/month USD → WordPress role: `reader`
- **Student** - $30/month USD → WordPress role: `student`  
- **Alumni** - $50/2 years USD → WordPress role: `alumnus`

### RCP Terminology
- **Membership** = **Subscription** (RCP treats these as equivalent)
- **Membership Level** = Subscription tier with pricing and role assignment
- **Registration** = New subscription creation process

## Payment Processing

### Payment Gateways
- **Stripe**: Primary payment processor
- **PayPal**: Secondary payment processor
- **Payment Collection**: All payments collected before access is granted (no free trials)

### Billing Options
- **Auto-Renewal**: Available for all membership levels
- **Manual Renewal**: Available as user option
- **Billing Cycles**: Monthly (Reader, Student), Biennial (Alumni)

### Cancellation Policy
- **User Control**: Users can cancel anytime via account billing page
- **Access Retention**: Users retain access until next billing cycle
- **Immediate Effect**: Cancellation processed but access continues until expiration

## WordPress Role Integration

### Automatic Role Assignment
- **Process**: RCP automatically assigns WordPress roles based on membership level
- **Implementation**: Handled natively by RCP (mechanism unknown to us)
- **Role Mapping**: Direct 1:1 relationship between membership level and WordPress role

### Role Transitions
```
Reader Membership → `reader` WordPress role
Student Membership → `student` WordPress role  
Alumni Membership → `alumnus` WordPress role
```

## Current Integration Points with Custom System

### Registration Hooks
- **Additional Fields**: Custom action hooks collect extra user data during registration
- **Data Validation**: Error handling hooks validate custom registration fields
- **Data Storage**: Save hooks store additional user information beyond RCP defaults

### Profile Management Hooks
- **Profile Editor**: Custom hooks extend RCP profile editor with additional fields
- **Data Updates**: Hooks handle saving updated profile information
- **Validation**: Error checking for profile modifications

### Post-Registration Automation
- **Trainer Assignment**: Triggered when registration completes
- **Welcome Emails**: Custom email system activated on successful registration
- **Email Template Override**: Custom action hook replaces default RCP email templates with themed versions

### LearnDash Integration
- **Access Control**: WordPress roles (assigned by RCP) control LearnDash content access
- **No Direct Integration**: RCP does not directly communicate with LearnDash
- **Role-Based Access**: Current system grants access to all content based on user's highest role level

## Current System Problems

### Duplicate Subscription Issues

#### Manual Reactivation Problem
- **Scenario**: Subscription expires → User attempts manual reactivation → Creates second subscription instead of reactivating first
- **Result**: User has multiple active subscriptions for same membership level
- **Business Rule Violation**: Should have only one subscription per membership type
- **Needed Solution**: System should reactivate existing subscription rather than create new one

#### Failed Payment Recovery Problem  
- **Scenario**: Payment fails → User manually creates new subscription → Original payment retries successfully
- **Result**: User double-billed with two active subscriptions
- **Impact**: Revenue disputes and customer service issues
- **Needed Solution**: Duplicate subscription detection and automatic cancellation of redundant subscriptions

### Duplicate Subscription Role Deactivation Issue
- **Scenario**: User has two subscriptions of same type → One subscription expires or is manually cancelled → Active subscription remains
- **Problem**: When duplicate subscription expires/cancels, user account is incorrectly marked as inactive
- **Expected**: User should remain active as long as one subscription of that type is active
- **Result**: User loses access despite having active subscription
- **Impact**: Customer service issues and legitimate users losing access
- **Needed Solution**: Role assignment should check all active subscriptions before deactivating user access

## Future Multi-Program Architecture

### Program-Specific Membership Structure (PLANNED)
- **Format**: `[program]_[level]` (e.g., `birth_doula_reader`, `postpartum_doula_student`)
- **Separation**: Each program will have its own set of Reader/Student/Alumni memberships
- **Independence**: Users can hold different membership levels across different programs

### Multi-Program Role Management Challenge

#### Multiple Role Assignment
- **Scenario**: User has `birth_doula_alumnus` + `postpartum_doula_student` subscriptions
- **WordPress Roles**: User would have both `alumnus` and `student` roles simultaneously
- **Access Problem**: Current system would grant Student access to all programs (highest permission wins)

#### Required Solution: Program-Specific Access Control
- **Need**: Role verification must check program-specific membership, not just global role
- **Logic**: User's access level per program should match their specific membership for that program
- **Example Access Matrix**:

| User | Birth Doula Membership | Postpartum Doula Membership | Birth Doula Access | Postpartum Access |
|------|------------------------|------------------------------|---------------------|------------------|
| John | Alumni | Student | Read-only + Own Assignments | Full Access |
| Jane | Student | Reader | Full Access | Read-only |

### User Experience for Multi-Program System
- **Checkout Process**: Separate checkout for each program (users pay for one program at a time)
- **Subscription Management**: Users will manage multiple program subscriptions independently
- **Billing Separation**: Each program subscription billed separately

### Administrative Management
- **Current Tools**: Standard RCP admin tools sufficient for program-specific membership management
- **No Custom Tools Needed**: Bulk management tools not required at this time

### RCP Membership Level Capacity
- **No Limits**: RCP can handle unlimited membership levels
- **Scalability**: System can accommodate program-specific membership expansion

## Critical Development Priorities

### Immediate Fixes Needed
1. **Duplicate Subscription Prevention**: Check for existing subscriptions before creating new ones
2. **Role Deactivation Fix**: Ensure user remains active when duplicate subscription expires but active subscription exists
3. **Subscription Cleanup Tools**: Admin tools to identify and resolve duplicate subscription scenarios
4. **Failed Payment Handling**: Improve retry logic to prevent double-billing scenarios

### Future Architecture Development
1. **Program-Specific Membership System**: Design and implement program-based subscription structure  
2. **Multi-Program User Interface**: Design user experience for managing multiple program subscriptions with separate checkouts
3. **Current Plugin Assessment**: Evaluate whether existing plugin architecture can handle multi-program requirements internally