import React, { Fragment, memo } from 'react';
import { FactoryProps } from '../../types/factory';
import { FactoryDetails } from './FactoryDetails';
import { FactoryOverview } from './FactoryOverview';

const Factory = memo((props: FactoryProps) => (
  <Fragment>
    <FactoryOverview {...props} />
    <FactoryDetails data={props.data} />
  </Fragment>
));

export default Factory;
Factory.displayName = 'Factory';
