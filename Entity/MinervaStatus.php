<?php

namespace Ice\MinervaClientBundle\Entity;

class MinervaStatus
{
    const ApplicationAccepted = 'APPLICATION_ACCEPTED';
    const ApplicationApprovedByAcademic = 'APPLICATION_APPROVED_BY_ACADEMIC';
    const ApplicationChecked = 'APPLICATION_CHECKED';
    const ApplicationInProgress = 'APPLICATION_IN_PROGRESS';
    const ApplicationLapsed = 'APPLICATION_LAPSED';
    const ApplicationReceived = 'APPLICATION_RECEIVED';
    const ApplicationRejected = 'APPLICATION_REJECTED';
    const ApplicationRejectedByAcademic = 'APPLICATION_REJECTED_BY_ACADEMIC';
    const ApplicationSentToAcademic = 'APPLICATION_SENT_TO_ACADEMIC';
    const ApplicationSentToApm = 'APPLICATION_SENT_TO_APM';
    const ApplicationWithdrawnByStudent = 'APPLICATION_WITHDRAWN_BY_STUDENT';
    const BursaryAwarded = 'BURSARY_AWARDED';
    const BursaryChecked = 'BURSARY_CHECKED';
    const BursaryDeclined = 'BURSARY_DECLINED';
    const BursaryLapsed = 'BURSARY_LAPSED';
    const BursaryReceived = 'BURSARY_RECEIVED';
    const BursaryRejected = 'BURSARY_REJECTED';
    const EnglishIsFirstLanguage = 'ENGLISH_IS_FIRST_LANGUAGE';
    const EnglishIsNotFirstLanguage = 'ENGLISH_IS_NOT_FIRST_LANGUAGE';
    const EnglishLanguageProficiencyProven = 'ENGLISH_LANGUAGE_PROFICIENCY_PROVEN';
    const OfferedJamesStuartBursary = 'OFFERED_JAMES_STUART_BURSARY';
    const WaitingForCaeCpeTest = 'WAITING_FOR_CAE_CPE_TEST';
    const WaitingForIeltsTest = 'WAITING_FOR_IELTS_TEST';
    const WaitingForLanguageCentreAssessment = 'WAITING_FOR_LANGUAGE_CENTRE_ASSESSMENT';
    const WaitingForToeflTest = 'WAITING_FOR_TOEFL_TEST';
    const RegistrationInProgress = 'REGISTRATION_IN_PROGRESS';
    const RegistrationComplete = 'REGISTRATION_COMPLETE';
    const RegistrationCancelled = 'REGISTRATION_CANCELLED';
    const PaymentArranged = 'PAYMENT_ARRANGED';
    const PaymentCommitted = 'PAYMENT_COMMITTED';
    const PaymentPartPaid = 'PAYMENT_PART_PAID';
    const PaymentBalanced = 'PAYMENT_BALANCED';
    const PaymentOverpaid = 'PAYMENT_OVERPAID';
    const EnrolmentReadyToEnrol = 'ENROLMENT_READY';
    const EnrolmentEnrolled = 'ENROLMENT_ENROLLED';
    const EnrolmentFailed = 'ENROLMENT_FAILED';
}