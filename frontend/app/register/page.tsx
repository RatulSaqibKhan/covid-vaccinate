import RegistrationFormContainer from "@/components/containers/RegistrationFormContainer";
import React, { Suspense } from "react";

export default async function RegisterPage() {
  return (
    <Suspense>
      <div className="p-4">
        <RegistrationFormContainer />
      </div>
    </Suspense>
  );
}
